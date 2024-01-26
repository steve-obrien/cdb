import{_ as p}from"./AuthenticatedLayout-9c0ef989.js";import{p as f,q as n,o as i,f as l,a as o,w as a,F as h,Z as g,k,b as t,s as m,l as y,n as v,d as x,t as w}from"./app-afe1df0b.js";import{_ as b}from"./_plugin-vue_export-helper-c27b6911.js";const C=f({props:{sessions:Array},components:{AuthenticatedLayout:p,Head:g,Link:k},computed:{linkChat(){return route("chat")}},methods:{isCurrent(e){return this.$page.props.sessionId==e},sessionName(e){return e.prompt?e.prompt.slice(0,25):e.id},linkSession(e){return route("chat.session",e)},deleteSession:async function(e){axios.delete(route("api.chatSessionDelete",e)).then(()=>{let r=this.sessions.findIndex(d=>d.id===e);r!==-1&&this.sessions.splice(r,1)})}}}),L=t("h2",{class:"font-semibold text-xl text-gray-800 dark:text-white leading-tight"},"Chat",-1),$={class:"flex h-full"},S={class:"hidden lg:flex h-full relative lg:w-60 xl:w-80 flex-col stretch bg-white dark:bg-gray-900 dark:text-gray-100"},N={class:"shrink p-2"},A=t("div",{class:"grow text-gray-800 dark:text-gray-300"},"Create new",-1),B=t("svg",{class:"w-6 h-6",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor"},[t("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"})],-1),H={class:"grow relative stretch"},D={class:"absolute inset-0 overflow-scroll space-y-2 p-2"},V=["onClick"],F={class:"@container h-full grow flex flex-col bg-gray-100 dark:bg-gray-900"};function I(e,r,d,Z,j,q){const u=n("Head"),c=n("Link"),_=n("AuthenticatedLayout");return i(),l(h,null,[o(u,{title:"Dashboard"}),o(_,null,{header:a(()=>[L]),default:a(()=>[t("div",$,[t("div",S,[t("div",N,[o(c,{href:e.linkChat,class:"flex"},{default:a(()=>[A,B]),_:1},8,["href"])]),t("div",H,[t("div",D,[(i(!0),l(h,null,m(e.sessions,s=>(i(),l("div",{class:"group border-b dark:border-gray-800 relative text-gray-800 dark:text-gray-300",key:s.id},[o(c,{class:v([{"font-bold":e.isCurrent(s.id)},"block"]),href:e.linkSession(s.id)},{default:a(()=>[x(w(e.sessionName(s)),1)]),_:2},1032,["class","href"]),t("button",{class:"hidden group-hover:block absolute top-0 right-0",onClick:z=>e.deleteSession(s.id)},"delete",8,V)]))),128))])])]),t("div",F,[y(e.$slots,"default")])])]),_:3})],64)}const G=b(C,[["render",I]]);export{G as default};