import{r as _,j as v,o as p,f as V,b as l,m as y,t as g,T as h,c as b,w as c,a as o,u as s,Z as x,d as f,k,n as $,e as q}from"./app-afe1df0b.js";import{_ as S}from"./GuestLayout-62e76e61.js";import{_ as d,a as i}from"./TextInput-95da6458.js";import{_ as m}from"./InputLabel-0bbf662f.js";import{P as B}from"./PrimaryButton-d9b92357.js";import"./_plugin-vue_export-helper-c27b6911.js";const P={class:"relative"},U=["value"],N={class:"pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3"},C={class:"text-gray-500 sm:text-sm",id:"price-currency"},D={__name:"DomainInput",props:{modelValue:{type:String,required:!0},host:String},emits:["update:modelValue"],setup(r,{expose:e}){const u=r,n=_(null);return v(()=>{n.value.hasAttribute("autofocus")&&n.value.focus()}),e({focus:()=>n.value.focus()}),(a,t)=>(p(),V("div",P,[l("input",y({type:"text"},u,{class:"border-gray-300 w-full focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm",value:r.modelValue,onInput:t[0]||(t[0]=w=>a.$emit("update:modelValue",w.target.value)),ref_key:"input",ref:n}),null,16,U),l("div",N,[l("span",C,"."+g(r.host),1)])]))}},E=["onSubmit"],R={class:"mt-4"},j={class:"mt-4"},A={class:"mt-4"},I={class:"mt-4"},L={class:"flex items-center justify-end mt-4"},Z={__name:"Register",props:{host:String},setup(r){const e=h({name:"",email:"",domain:"",password:"",password_confirmation:""}),u=()=>{e.post(route("register"),{onFinish:()=>e.reset("password","password_confirmation")})};return(n,a)=>(p(),b(S,null,{default:c(()=>[o(s(x),{title:"Register"}),l("form",{onSubmit:q(u,["prevent"])},[l("div",null,[f(g(r.host[0])+" HELLO? ",1),o(m,{for:"name",value:"Name"}),o(d,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:s(e).name,"onUpdate:modelValue":a[0]||(a[0]=t=>s(e).name=t),required:"",autofocus:"",autocomplete:"name"},null,8,["modelValue"]),o(i,{class:"mt-2",message:s(e).errors.name},null,8,["message"])]),l("div",R,[o(m,{for:"email",value:"Email"}),o(d,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":a[1]||(a[1]=t=>s(e).email=t),required:"",autocomplete:"username"},null,8,["modelValue"]),o(i,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),l("div",j,[o(m,{for:"domain",value:"Domain"}),o(D,{id:"domain",class:"mt-1 block w-full",modelValue:s(e).domain,"onUpdate:modelValue":a[2]||(a[2]=t=>s(e).domain=t),"data-hello":"wefwef",required:"",host:r.host[0],autocomplete:"username"},null,8,["modelValue","host"]),o(i,{class:"mt-2",message:s(e).errors.domain},null,8,["message"])]),l("div",A,[o(m,{for:"password",value:"Password"}),o(d,{id:"password",type:"password",class:"mt-1 block w-full",modelValue:s(e).password,"onUpdate:modelValue":a[3]||(a[3]=t=>s(e).password=t),required:"",autocomplete:"new-password"},null,8,["modelValue"]),o(i,{class:"mt-2",message:s(e).errors.password},null,8,["message"])]),l("div",I,[o(m,{for:"password_confirmation",value:"Confirm Password"}),o(d,{id:"password_confirmation",type:"password",class:"mt-1 block w-full",modelValue:s(e).password_confirmation,"onUpdate:modelValue":a[4]||(a[4]=t=>s(e).password_confirmation=t),required:"",autocomplete:"new-password"},null,8,["modelValue"]),o(i,{class:"mt-2",message:s(e).errors.password_confirmation},null,8,["message"])]),l("div",L,[o(s(k),{href:n.route("login"),class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:c(()=>[f(" Already registered? ")]),_:1},8,["href"]),o(B,{class:$(["ml-4",{"opacity-25":s(e).processing}]),disabled:s(e).processing},{default:c(()=>[f(" Register ")]),_:1},8,["class","disabled"])])],40,E)]),_:1}))}};export{Z as default};