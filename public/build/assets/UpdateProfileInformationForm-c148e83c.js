import{K as N,r as b,T as I,o as u,c as x,w as s,d as r,b as o,a,i as p,z as v,u as e,E,e as _,g as f,f as h,t as U,j as $,A}from"./app-689c2a59.js";import{_ as g}from"./InputError-4f107301.js";import{_ as y}from"./InputLabel-4e686b7c.js";import{P as D}from"./PrimaryButton-4bd3cbf3.js";import{_ as w}from"./SecondaryButton-cf05c5fa.js";import{_ as P}from"./TextInput-327f0586.js";import{_ as F}from"./ActionSection-509ff2a2.js";import"./_plugin-vue_export-helper-c27b6911.js";const R=["onSubmit"],T={class:"col-span-6 sm:col-span-4"},j={class:"mt-2"},q=["src","alt"],z={class:"mt-2"},K=["value"],L={key:0},M={class:"text-sm mt-2 text-gray-800"},Y={class:"mt-2 font-medium text-sm text-green-600"},G={class:"flex items-center gap-4"},H={key:0,class:"text-sm text-gray-600"},oe={__name:"UpdateProfileInformationForm",props:{mustVerifyEmail:{type:Boolean},status:{type:String}},setup(k){const n=N().props.auth.user,i=b(null),c=b(null),t=I({name:n.name,email:n.email,photo:null}),V=()=>{i.value.files.length&&(t.photo=i.value.files[0]),t.post(route("profile.update"),{forceFormData:!0,errorBag:"updateProfileInformation",preserveScroll:!0})},S=()=>{const d=i.value.files[0];if(!d)return;const l=new FileReader;l.onload=m=>{c.value=m.target.result},l.readAsDataURL(d)},C=()=>{i.value.click()},B=()=>{axios.delete(route("profile.avatar.delete")).then(()=>{location.reload()})};return(d,l)=>(u(),x(F,null,{title:s(()=>[r("Profile Information")]),description:s(()=>[r("Update your account's profile information and email address.")]),content:s(()=>[o("form",{onSubmit:_(V,["prevent"]),class:"mt-6 space-y-6"},[o("div",T,[o("input",{id:"photo",name:"photo",ref_key:"photoInput",ref:i,type:"file",class:"hidden",onChange:S},null,544),a(y,{for:"photo",value:"Photo"}),p(o("div",j,[o("img",{src:e(n).avatar_url,alt:e(n).name,class:"rounded-full h-20 w-20 object-cover"},null,8,q)],512),[[v,!c.value]]),p(o("div",z,[o("span",{class:"block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center",style:E("background-image: url('"+c.value+"');")},null,4)],512),[[v,c.value]]),a(w,{class:"mt-2 me-2",type:"button",onClick:_(C,["prevent"])},{default:s(()=>[r(" Select A New Photo ")]),_:1},8,["onClick"]),e(n).avatar_url?(u(),x(w,{key:0,type:"button",class:"mt-2",onClick:_(B,["prevent"])},{default:s(()=>[r(" Remove Photo ")]),_:1},8,["onClick"])):f("",!0),e(t).progress?(u(),h("progress",{key:1,value:e(t).progress.percentage,max:"100"},U(e(t).progress.percentage)+"% ",9,K)):f("",!0),a(g,{message:e(t).errors.photo,class:"mt-2"},null,8,["message"])]),o("div",null,[a(y,{for:"name",value:"Name"}),a(P,{id:"name",type:"text",class:"mt-1 block w-full",modelValue:e(t).name,"onUpdate:modelValue":l[0]||(l[0]=m=>e(t).name=m),required:"",autofocus:"",autocomplete:"name"},null,8,["modelValue"]),a(g,{class:"mt-2",message:e(t).errors.name},null,8,["message"])]),o("div",null,[a(y,{for:"email",value:"Email"}),a(P,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:e(t).email,"onUpdate:modelValue":l[1]||(l[1]=m=>e(t).email=m),required:"",autocomplete:"username"},null,8,["modelValue"]),a(g,{class:"mt-2",message:e(t).errors.email},null,8,["message"])]),k.mustVerifyEmail&&e(n).email_verified_at===null?(u(),h("div",L,[o("p",M,[r(" Your email address is unverified. "),a(e($),{href:d.route("verification.send"),method:"post",as:"button",class:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"},{default:s(()=>[r(" Click here to re-send the verification email. ")]),_:1},8,["href"])]),p(o("div",Y," A new verification link has been sent to your email address. ",512),[[v,k.status==="verification-link-sent"]])])):f("",!0),o("div",G,[a(D,{disabled:e(t).processing},{default:s(()=>[r("Save")]),_:1},8,["disabled"]),a(A,{"enter-active-class":"transition ease-in-out","enter-from-class":"opacity-0","leave-active-class":"transition ease-in-out","leave-to-class":"opacity-0"},{default:s(()=>[e(t).recentlySuccessful?(u(),h("p",H,"Saved.")):f("",!0)]),_:1})])],40,R)]),_:1}))}};export{oe as default};
