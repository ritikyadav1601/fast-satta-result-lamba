import AdminApp from '@/components/AdminApp';
const map={'home-page':'results','game':'results','bulk-result':'results','extra-game':'results','extra-game-result':'results','other-chart':'khaiwalCharts','question':'results','faq':'results','blogs':'results','users':'results','setting':'results','dashboard':'results'};
export default async function AdminPage({params}){const {path=[]}=await params;return <AdminApp initialSection={map[path[0]]||'games'}/>}
