import{T as p,o as u,c as f,w as i,a as e,u as t,Z as _,b as o,d as m,t as w,n as v,e as b}from"./app-1ca54d9f.js";import{_ as g}from"./GuestLayout-5d718916.js";import{_ as h}from"./InputError-651f7ec8.js";import{_ as n}from"./InputLabel-2d500b26.js";import{P as V}from"./PrimaryButton-856ec3c0.js";import{_ as k}from"./PasswordInput-a9202765.js";import"./_plugin-vue_export-helper-c27b6911.js";const x=["onSubmit"],y={class:"mt-4"},B={class:"flex items-center justify-end mt-4"},q={__name:"InviteRegister",props:{invite:{type:Object}},setup(a){const l=a,s=p({password:"",remember:!0}),d=()=>{s.post(route("team.invite.register",{token:l.invite.token}),{onFinish:()=>s.reset("password")})};return($,r)=>(u(),f(g,null,{default:i(()=>[e(t(_),{title:"Register"}),o("form",{onSubmit:b(d,["prevent"])},[o("div",null,[e(n,{for:"email",value:"Email"}),m(" "+w(a.invite.email),1)]),o("div",y,[e(n,{for:"password",value:"Choose a password"}),e(k,{id:"password",class:"mt-1 block w-full",modelValue:t(s).password,"onUpdate:modelValue":r[0]||(r[0]=c=>t(s).password=c),required:"",autocomplete:"new-password"},null,8,["modelValue"]),e(h,{class:"mt-2",message:t(s).errors.password},null,8,["message"])]),o("div",B,[e(V,{class:v(["ml-4",{"opacity-25":t(s).processing}]),disabled:t(s).processing},{default:i(()=>[m(" Register ")]),_:1},8,["class","disabled"])])],40,x)]),_:1}))}};export{q as default};
