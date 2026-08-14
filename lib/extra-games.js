import { MongoClient, ObjectId } from 'mongodb';
import { gameSlug } from './game-slug';
import { today } from './store';

const uri = process.env.EXTRA_GAMES_MONGO_URI;
const state = globalThis.__fskExtraMongo ??= { promise: null };
const options = { appName:'fast-satta-result-extra-games', serverSelectionTimeoutMS:6000, connectTimeoutMS:6000, socketTimeoutMS:30000, maxPoolSize:10, maxIdleTimeMS:60000, retryReads:true };

async function extraDatabase() {
  if (!uri) return null;
  if (!state.promise) {
    const mongo = new MongoClient(uri, options);
    state.promise = mongo.connect().then(() => mongo).catch(async error => { state.promise=null; await mongo.close().catch(()=>{}); throw error; });
  }
  return (await state.promise).db();
}

export async function getExtraGamesData() {
  try {
    const db = await extraDatabase();
    if (!db) return { games: [], results: [] };
    const currentDate=today(),monthStart=`${currentDate.slice(0,7)}-01`;
    const [games, results] = await Promise.all([
      db.collection('games').find({isActive:true},{projection:{name:1,code:1,resultTime:1,showIndex:1}}).sort({showIndex:1,resultTime:1,name:1}).toArray(),
      db.collection('gameresults').find(
        {resultDate:{$gte:monthStart,$lte:currentDate}},
        {projection:{resultDate:1,game:1,result:1}},
      ).toArray(),
    ]);
    return { games: games.map(({_id,...game})=>({...game,id:String(_id),source:'extra'})), results: results.map(({_id,game,...row})=>({...row,id:String(_id),gameId:String(game)})) };
  } catch (error) { console.error('Extra games MongoDB unavailable.', error.name); return { games: [], results: [] }; }
}

export async function getExtraGame(id) {
  const db = await extraDatabase();
  if (!db || !ObjectId.isValid(id)) return null;
  const game = await db.collection('games').findOne({_id:new ObjectId(id),isActive:true},{projection:{name:1,code:1,resultTime:1}});
  if (!game) return null;
  const results = await db.collection('gameresults').find({game:game._id},{projection:{resultDate:1,result:1}}).sort({resultDate:-1}).toArray();
  return { game:{id:String(game._id),name:game.name,code:game.code,resultTime:game.resultTime}, results:results.map(row=>({id:String(row._id),date:row.resultDate,result:row.result})) };
}

export async function getExtraGameBySlug(slug) {
  const db = await extraDatabase();
  if (!db) return null;
  const games = await db.collection('games').find({isActive:true},{projection:{name:1,code:1,resultTime:1}}).toArray();
  const game = games.find(item=>gameSlug(item.name)===slug);
  if (!game) return null;
  const results = await db.collection('gameresults').find({game:game._id},{projection:{resultDate:1,result:1}}).sort({resultDate:-1}).toArray();
  return { game:{id:String(game._id),name:game.name,code:game.code,resultTime:game.resultTime}, results:results.map(row=>({id:String(row._id),date:row.resultDate,result:row.result})) };
}
