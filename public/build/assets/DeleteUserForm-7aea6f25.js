import{o as p,f as w,s as g,r as m,T as x,c as h,w as e,d as o,a as t,b as c,u as r,y as b,n as k,x as v}from"./app-689c2a59.js";import{_ as $}from"./_plugin-vue_export-helper-c27b6911.js";import{_ as D}from"./InputError-4f107301.js";import{_ as C}from"./InputLabel-4e686b7c.js";import{_ as B}from"./SecondaryButton-cf05c5fa.js";import{_ as V}from"./TextInput-327f0586.js";import{_ as A}from"./ActionSection-509ff2a2.js";import{_ as U}from"./DialogModal-585a3ab3.js";const P={},K={class:"inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"};function N(d,a){return p(),w("button",K,[g(d.$slots,"default")])}const f=$(P,[["render",N]]),S=c("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain. ",-1),T=c("p",{class:"mt-1 text-sm text-gray-600"}," Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account. ",-1),E={class:"mt-6"},J={__name:"DeleteUserForm",setup(d){const a=m(!1),n=m(null),s=x({password:""}),_=()=>{a.value=!0,v(()=>n.value.focus())},i=()=>{s.delete(route("profile.destroy"),{preserveScroll:!0,onSuccess:()=>l(),onError:()=>n.value.focus(),onFinish:()=>s.reset()})},l=()=>{a.value=!1,s.reset()};return(F,u)=>(p(),h(A,null,{title:e(()=>[o("Delete Account")]),description:e(()=>[o(" Permanently delete account ")]),content:e(()=>[S,t(f,{class:"mt-4",onClick:_},{default:e(()=>[o("Delete Account")]),_:1}),t(U,{show:a.value,onClose:l},{title:e(()=>[o(" Delete Account ")]),content:e(()=>[o(" Are you sure you want to delete your account? "),T,c("div",E,[t(C,{for:"password",value:"Password",class:"sr-only"}),t(V,{id:"password",ref_key:"passwordInput",ref:n,modelValue:r(s).password,"onUpdate:modelValue":u[0]||(u[0]=y=>r(s).password=y),type:"password",class:"mt-1 block w-3/4",placeholder:"Password",onKeyup:b(i,["enter"])},null,8,["modelValue","onKeyup"]),t(D,{message:r(s).errors.password,class:"mt-2"},null,8,["message"])])]),footer:e(()=>[t(B,{onClick:l},{default:e(()=>[o(" Cancel ")]),_:1}),t(f,{class:k(["ml-3",{"opacity-25":r(s).processing}]),disabled:r(s).processing,onClick:i},{default:e(()=>[o(" Delete Account ")]),_:1},8,["class","disabled"])]),_:1},8,["show"])]),_:1}))}};export{J as default};
