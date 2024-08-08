import{r as k,G as V,B as $,o as l,f as i,a as r,u as p,w as u,F as _,Z as j,b as e,i as b,H as E,n as g,d as h,t as m,g as d,q as w,I as A,e as B,J as I,L as U,M as G,N as M}from"./app-141596ac.js";import{_ as O,S as q,U as z,h as y,G as D,r as F,V as L}from"./AuthenticatedLayout-5d316374.js";import{_ as H}from"./_plugin-vue_export-helper-c27b6911.js";const v=x=>(G("data-v-b417ddb2"),x=x(),M(),x),J=v(()=>e("h2",{class:"font-semibold text-xl text-gray-800 dark:text-white leading-tight"},"Team",-1)),P={class:"p-4 md:p-8"},Y={class:"sm:hidden"},Z=v(()=>e("label",{for:"tabs",class:"sr-only"},"Select a tab",-1)),K=["selected"],Q=["selected"],R={class:"hidden sm:block"},W={class:"border-b border-gray-200"},X={class:"-mb-px flex justify-between space-x-8","aria-label":"Tabs"},ee={class:"flex space-x-8"},te=["aria-current"],se=["aria-current"],ae={key:0,class:"flow-root"},oe={class:"-mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8"},ne={class:"inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"},le={class:"bg-white dark:bg-gray-900 py-4 px-7 grid grid-cols-3 my-2 rounded-sm"},ie={class:"flex items-center"},re=["src"],de={class:"font-bold text-lg text-gray-900 dark:text-gray-100"},ce={key:0,class:"bg-gray-200 dark:bg-gray-700 rounded-md px-2 ml-2"},ue={class:"flex"},me={class:"font-normal text-lg text-gray-700 dark:text-gray-300"},xe={class:"flex"},pe={class:"font-normal text-lg text-gray-700 dark:text-gray-300"},ve={key:1,class:"flow-root"},ge={class:"-mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8"},fe={class:"inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"},_e={key:0},be={class:"flex items-center"},he={class:"font-bold text-lg"},ye={class:"flex"},ke={class:"font-normal text-lg"},we={class:"flex"},Ie={class:"font-normal text-lg"},Ue={class:"flex text-right"},Ce=["onClick"],Ne={key:1},Te=v(()=>e("div",{class:"fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-opacity-75"},null,-1)),Se={class:"fixed inset-0 overflow-hidden"},Ve={class:"absolute inset-0 overflow-hidden"},$e={class:"pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10"},je={class:"absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4"},Ee=v(()=>e("span",{class:"absolute -inset-2.5"},null,-1)),Ae=v(()=>e("span",{class:"sr-only"},"Close panel",-1)),Be={class:"flex h-full flex-col overflow-y-scroll bg-white dark:bg-gray-800 py-6 shadow-xl"},Ge={class:"px-4 sm:px-6"},Me={class:"relative mt-6 flex-1 px-4 sm:px-6"},Oe=["onSubmit"],qe=v(()=>e("label",{for:"name",class:"text-sm text-gray-700 dark:text-gray-300 mb-2"},"Name",-1)),ze={key:0,class:"text-red-600"},De=v(()=>e("label",{for:"email",class:"text-sm text-gray-700 dark:text-gray-300 mb-2"},"Email",-1)),Fe={key:0,class:"text-red-600"},Le=v(()=>e("div",null,[e("button",{tabindex:"1",class:"bg-black dark:bg-gray-700 px-3 py-3 w-full text-center text-sm font-semibold text-white shadow-sm rounded-full",type:"submit"},"Invite User")],-1)),He={key:1,class:"flex flex-col"},Je=v(()=>e("div",{class:"bg-gray-100 dark:bg-gray-700 w-[350px] h-[350px] mx-auto flex flex-col items-center justify-center p-20 text-center rounded-full"},[e("span",{class:"text-xl font-medium dark:text-gray-100"},"New user added to workspace!"),e("p",{class:"dark:text-gray-300"},"An invitation has been emailed with directions on how to complete their account creation.")],-1)),Pe={__name:"Team",props:{auth:{type:Object},users:{type:Object},invites:{type:Object}},setup(x){const C=x,c=k([]),n=k("Users"),s=V({state:!1,name:null,email:null,errors:{}});window.invite=s,$(()=>C.invites,o=>{c.value=o},{immediate:!0}),window.localInvites=c;const N=()=>{s.state="open",s.name="",s.email="",Object.keys(s.errors).forEach(o=>delete s.errors[o])},f=()=>{s.state="closed"},T=async()=>{try{const o=await U.post(route("team.invite"),s);console.log("User added successfully:",o),o.data.invite.isNew=!0,c.value.unshift(o.data.invite),s.name="",s.email="",s.state="success",n.value="Invites",setTimeout(()=>{o.data.invite.isNew=!1},2e3)}catch(o){o.response&&o.response.data&&o.response.data.errors?s.errors=o.response.data.errors:console.error("An error occurred:",o)}},S=async o=>{U.delete(route("team.invite.delete",{token:o})).then(()=>{const a=c.value.findIndex(t=>t.token===o);a!==-1&&c.value.splice(a,1)})};return(o,a)=>(l(),i(_,null,[r(p(j),{title:"Team"}),r(O,{class:"bg-gray-100 dark:bg-black"},{header:u(()=>[J]),default:u(()=>[e("div",P,[e("div",null,[e("div",Y,[Z,b(e("select",{"onUpdate:modelValue":a[0]||(a[0]=t=>n.value=t),onChange:a[1]||(a[1]=t=>n.value=this.value),id:"tabs",name:"tabs",class:"block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"},[e("option",{selected:n.value=="Users"},"Users",8,K),e("option",{selected:n.value=="Invites"},"Invites",8,Q)],544),[[E,n.value]])]),e("div",R,[e("div",W,[e("nav",X,[e("div",ee,[e("a",{href:"#",onClick:a[2]||(a[2]=t=>n.value="Users"),class:g([n.value=="Users"?"border-indigo-500 text-indigo-600":"border-transparent text-gray-500 hover:border-gray-200 hover:text-gray-700","flex whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium"]),"aria-current":n.value.current?"page":void 0},[h(" Users "),x.users.length?(l(),i("span",{key:0,class:g([n.value=="Users"?"bg-indigo-100 text-indigo-600":"bg-gray-100 text-gray-900","ml-3 hidden rounded-full py-0.5 px-2.5 text-xs font-medium md:inline-block"])},m(x.users.length),3)):d("",!0)],10,te),e("a",{href:"#",onClick:a[3]||(a[3]=t=>n.value="Invites"),class:g([n.value=="Invites"?"border-indigo-500 text-indigo-600":"border-transparent text-gray-500 hover:border-gray-200 hover:text-gray-700","flex whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium"]),"aria-current":n.value.current?"page":void 0},[h(" Invites "),c.value.length?(l(),i("span",{key:0,class:g([n.value=="Invites"?"bg-indigo-100 text-indigo-600":"bg-gray-100 text-gray-900","ml-3 hidden rounded-full py-0.5 px-2.5 text-xs font-medium md:inline-block"])},m(c.value.length),3)):d("",!0)],10,se)]),e("button",{onClick:a[4]||(a[4]=t=>N()),type:"button",class:"ml-auto block my-2 rounded-full bg-black px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"},"Add user")])])])]),n.value=="Users"?(l(),i("div",ae,[e("div",oe,[e("div",ne,[(l(!0),i(_,null,w(x.users,t=>(l(),i("div",le,[e("div",ie,[e("img",{class:"w-8 rounded-full mr-2",src:t.avatar_url},null,8,re),e("span",de,m(t.name),1),x.auth.user.id==t.id?(l(),i("span",ce,"You")):d("",!0)]),e("div",ue,[e("span",me,m(t.email),1)]),e("div",xe,[e("span",pe,m(t.provider??"Email + Password"),1)])]))),256))])])])):d("",!0),n.value=="Invites"?(l(),i("div",ve,[e("div",ge,[e("div",fe,[c.value.length?(l(),i("div",_e,[r(A,{name:"flash",tag:"div"},{default:u(()=>[(l(!0),i(_,null,w(c.value,t=>(l(),i("div",{key:t.email,class:g(["group bg-white py-4 px-7 grid grid-cols-4 my-2 rounded-sm",{"new-item":t.isNew}])},[e("div",be,[e("span",he,m(t.name),1)]),e("div",ye,[e("span",ke,m(t.email),1)]),e("div",we,[e("span",Ie,m(t.created_at),1)]),e("div",Ue,[e("button",{onClick:Ye=>S(t.token),class:"hidden text-red-600 group-hover:block font-normal text-lg"},"delete",8,Ce)])],2))),128))]),_:1})])):d("",!0),c.value.length==0?(l(),i("div",Ne," Invite your team! ")):d("",!0)])])])):d("",!0)]),r(p(q),{as:"template",show:["open","success"].includes(s.state)},{default:u(()=>[r(p(z),{class:"relative z-50",onClose:a[9]||(a[9]=t=>f())},{default:u(()=>[r(p(y),{as:"template",enter:"ease-in-out duration-500","enter-from":"opacity-0","enter-to":"opacity-100",leave:"ease-in-out duration-500","leave-from":"opacity-100","leave-to":"opacity-0"},{default:u(()=>[Te]),_:1}),e("div",Se,[e("div",Ve,[e("div",$e,[r(p(y),{as:"template",enter:"transform transition ease-in-out duration-500 sm:duration-700","enter-from":"translate-x-full","enter-to":"translate-x-0",leave:"transform transition ease-in-out duration-500 sm:duration-700","leave-from":"translate-x-0","leave-to":"translate-x-full"},{default:u(()=>[r(p(D),{class:"pointer-events-auto relative w-screen max-w-md"},{default:u(()=>[r(p(y),{as:"template",enter:"ease-in-out duration-500","enter-from":"opacity-0","enter-to":"opacity-100",leave:"ease-in-out duration-500","leave-from":"opacity-100","leave-to":"opacity-0"},{default:u(()=>[e("div",je,[e("button",{type:"button",class:"relative rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white dark:focus:ring-gray-600",onClick:a[5]||(a[5]=t=>f())},[Ee,Ae,r(p(F),{class:"h-6 w-6","aria-hidden":"true"})])])]),_:1}),e("div",Be,[e("div",Ge,[r(p(L),{class:"text-xl font-semibold leading-6 text-gray-900 dark:text-gray-100"},{default:u(()=>[h(" + Add User ")]),_:1})]),e("div",Me,[s.state=="open"?(l(),i("form",{key:0,class:"grid gap-5",onSubmit:B(T,["prevent"])},[e("div",null,[qe,b(e("input",{"onUpdate:modelValue":a[6]||(a[6]=t=>s.name=t),required:"",id:"name",name:"name",type:"text",tabindex:"1",class:"bg-gray-100 dark:bg-gray-700 w-full rounded-md border-2 dark:border-gray-600 focus:ring-0 focus:ring-black dark:focus:ring-white px-5 py-3",placeholder:"Name"},null,512),[[I,s.name]]),s.errors.name?(l(),i("span",ze,m(s.errors.name[0]),1)):d("",!0)]),e("div",null,[De,b(e("input",{"onUpdate:modelValue":a[7]||(a[7]=t=>s.email=t),required:"",id:"email",name:"email",type:"email",tabindex:"1",class:"bg-gray-100 dark:bg-gray-700 w-full rounded-md border-2 dark:border-gray-600 focus:ring-0 focus:ring-black dark:focus:ring-white px-5 py-3",placeholder:"Email"},null,512),[[I,s.email]]),s.errors.email?(l(),i("span",Fe,m(s.errors.email[0]),1)):d("",!0)]),Le],40,Oe)):d("",!0),s.state=="success"?(l(),i("div",He,[Je,e("button",{onClick:a[8]||(a[8]=t=>f()),tabindex:"1",class:"mt-10 bg-black dark:bg-gray-700 px-5 py-3 mx-auto text-center text-sm font-semibold text-white shadow-sm rounded-full"},"Thanks!")])):d("",!0)])])]),_:1})]),_:1})])])])]),_:1})]),_:1},8,["show"])]),_:1})],64))}},Re=H(Pe,[["__scopeId","data-v-b417ddb2"]]);export{Re as default};
