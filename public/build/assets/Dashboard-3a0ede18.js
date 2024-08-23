import{_ as c}from"./AuthenticatedLayout-e0ef55ae.js";import{l as m,j as _,p as s,o as h,f as p,a as o,w as n,F as x,b as e}from"./app-5a04eb8f.js";import{_ as u}from"./_plugin-vue_export-helper-c27b6911.js";const f=m({components:{AuthenticatedLayout:c,Link:_},tennants:{type:Object},methods:{newdb(){const t=prompt("Please enter your name","Harry Potter");axios.post(route("db.new",{}),{prompt:t})}}}),v=e("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"},"Dashboard",-1),b={class:"p-4 md:p-8"},w={class:"mt-8 flow-root"},g={class:"-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"},k={class:"inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"},y=e("div",null,[e("div",{class:"text-7xl text-center w-full"},"🤖"),e("div",{class:"text-lg text-center w-full"},"Chat to Ni AI")],-1),C=e("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor","aria-hidden":"true","data-slot":"icon",class:"h-6 w-6 shrink-0"},[e("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"})],-1),L={class:"p-4 md:p-8"},A=e("div",{class:"sm:flex sm:items-center"},[e("div",{class:"sm:flex-auto"},[e("h1",{class:"text-base font-semibold leading-6 text-gray-900"},"Databases"),e("p",{class:"mt-2 text-sm text-gray-700"},"Lets make new databases for shits and giggles.")])],-1),B={class:"mt-8 flow-root"},D={class:"-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8"},$={class:"inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"},N=e("div",null,[e("div",{class:"text-7xl text-center w-full"},"+"),e("div",{class:"text-lg text-center w-full"},"Create DB")],-1),V=[N];function j(t,a,F,H,P,E){const l=s("Head"),d=s("Link"),r=s("AuthenticatedLayout");return h(),p(x,null,[o(l,{title:"Dashboard"}),o(r,null,{header:n(()=>[v]),default:n(()=>[e("div",b,[e("div",w,[e("div",g,[e("div",k,[o(d,{href:t.route("chat"),class:"w-64 h-52 border items-center border-gray-300 hover:border-pink-700 border-dashed rounded-md p-6 flex flex-col"},{default:n(()=>[y,C]),_:1},8,["href"])])])])]),e("div",L,[A,e("div",B,[e("div",D,[e("div",$,[e("button",{onClick:a[0]||(a[0]=(...i)=>t.newdb&&t.newdb(...i)),class:"w-64 h-52 border items-center border-gray-300 hover:border-pink-700 border-dashed rounded-md p-6 flex flex-col"},V)])])])])]),_:1})],64)}const q=u(f,[["render",j]]);export{q as default};