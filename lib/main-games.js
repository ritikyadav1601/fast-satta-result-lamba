import { MongoClient, ObjectId } from 'mongodb';
import { today } from './store';
import { gameSlug } from './game-slug';

const uri=process.env.MAIN_GAMES_MONGO_URI;
const state=globalThis.__fskMainGamesMongo??={promise:null};
const mainNames=new Set(['delhi bazar','shri ganesh','faridabad','ghaziabad','gali','desawar']);
const normalize=name=>String(name||'').trim().toLowerCase().replace(/[^a-z]+/g,' ').trim().replace('desawer','desawar');

async function mainDatabase(){
  if(!uri)return null;
  if(!state.promise){const mongo=new MongoClient(uri,{appName:'fast-satta-result-main-games',serverSelectionTimeoutMS:6000,connectTimeoutMS:6000,socketTimeoutMS:30000,maxPoolSize:10,maxIdleTimeMS:60000,retryReads:true});state.promise=mongo.connect().then(()=>mongo).catch(async error=>{state.promise=null;await mongo.close().catch(()=>{});throw error})}
  return (await state.promise).db();
}

const mapGame=game=>({id:String(game._id),name:game.name,englishName:normalize(game.name),time:game.resultTime,resultTime:game.resultTime,status:game.isActive!==false,showIndex:game.showIndex,source:'main'});
const mapResult=row=>({id:String(row._id),gameId:String(row.game),date:row.resultDate,result:row.result});

export async function getSplitMainGames(){
  try {
    const db=await mainDatabase();
    if(!db)return {games:[],results:[],otherGames:[],otherResults:[]};
    const games=await db.collection('games').find({isActive:true}).sort({showIndex:1,resultTime:1,name:1}).toArray();
    const date=today();
    const monthStart=`${date.slice(0,7)}-01`;
    const results=await db.collection('gameresults').find({resultDate:{$gte:monthStart,$lte:date}}).toArray();
    const main=games.filter(game=>mainNames.has(normalize(game.name))),others=games.filter(game=>!mainNames.has(normalize(game.name)));
    const mainIds=new Set(main.map(game=>String(game._id)));
    return {games:main.map(mapGame),results:results.filter(row=>mainIds.has(String(row.game))).map(mapResult),otherGames:others.map(mapGame),otherResults:results.filter(row=>!mainIds.has(String(row.game))).map(mapResult)};
  } catch (error) { console.error('Main games MongoDB unavailable.', error.name); return {games:[],results:[],otherGames:[],otherResults:[]}; }
}

export async function getMainChartData(){
  const db=await mainDatabase();if(!db)return {games:[],results:[]};
  const games=await db.collection('games').find({isActive:true}).sort({showIndex:1,resultTime:1,name:1}).toArray();
  const ids=games.map(game=>game._id),results=await db.collection('gameresults').find({game:{$in:ids}}).toArray();
  return {games:games.map(mapGame),results:results.map(mapResult)};
}

export async function getMainGame(id){
  const db=await mainDatabase();if(!db||!ObjectId.isValid(id))return null;
  const game=await db.collection('games').findOne({_id:new ObjectId(id),isActive:true});if(!game)return null;
  const results=await db.collection('gameresults').find({game:game._id}).sort({resultDate:-1}).toArray();
  return {game:mapGame(game),results:results.map(mapResult)};
}

export async function getMainGameBySlug(slug){
  const db=await mainDatabase();if(!db)return null;
  const games=await db.collection('games').find({isActive:true}).toArray();
  const game=games.find(item=>gameSlug(item.name)===slug);if(!game)return null;
  const results=await db.collection('gameresults').find({game:game._id}).sort({resultDate:-1}).toArray();
  return {game:mapGame(game),results:results.map(mapResult)};
}
