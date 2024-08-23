import{_}from"./AuthenticatedLayout-e0ef55ae.js";import{l as y,p as d,o as s,f as r,a as l,w as h,F as u,Z as k,j as C,b as a,q as g,n as x,t as b,A as P,d as S,g as B}from"./app-5a04eb8f.js";import L from"./PromptForm-8bac1295.js";import I from"./ChatMessage-9faa2b64.js";import E from"./ChatList-c1060d01.js";import{E as F}from"./Editor-76d816a6.js";import{_ as M}from"./_plugin-vue_export-helper-c27b6911.js";const T=y({props:{user:Object,components:[]},components:{AuthenticatedLayout:_,Head:k,Link:C,PromptForm:L,ChatMessage:I,ChatList:E,Editor:F},data(){return{state:"list",code:"",messages:[],placeholder:"",examples:[{prompt:"A kanban board",label:"Kanban"},{prompt:"A landing page hero section with a heading, leading text and an opt-in form.",label:"Landing Page"},{prompt:"A contact form with first name, last name, email, and message fields. Put the form in a card with a submit button.",label:"Contact Form"},{prompt:"A dashboard with a sidebar nav and a table",label:"Dashboard"},{prompt:"An ecommerce dashboard with a sidebar navigation and a table of recent orders.",label:"Ecommerce"},{prompt:"A user profile card with a profile picture, name, and bio.",label:"Profile Card"},{prompt:"A blog post layout with a title, featured image, and content area.",label:"Blog Post"},{prompt:"A pricing table with three columns for different plans, each with a title, features list, and a call-to-action button.",label:"Pricing Table"},{prompt:"A product card with an image, product name, price, and add-to-cart button.",label:"Product Card"},{prompt:"A sidebar navigation menu with icons and labels for each item.",label:"Sidebar Menu"},{prompt:"A hero section with a background image, headline, subheadline, and a call-to-action button.",label:"Hero Section"},{prompt:"A login form with email and password fields, a submit button, and a 'forgot password' link.",label:"Login Form"},{prompt:"A registration form with fields for username, email, password, and a submit button.",label:"Registration Form"},{prompt:"A modal dialog with a title, content area, and action buttons.",label:"Modal Dialog"},{prompt:"A footer with columns for links, contact information, and social media icons.",label:"Footer"},{prompt:"A search bar with an input field and a search button.",label:"Search Bar"},{prompt:"A notification banner with an icon, message text, and a close button.",label:"Notification Banner"},{prompt:"A task list with checkboxes, task descriptions, and a button to add new tasks.",label:"Task List"},{prompt:"A calendar view with days of the week and scheduled events.",label:"Calendar View"},{prompt:"A testimonial section with quotes, author names, and author photos.",label:"Testimonial Section"},{prompt:"A breadcrumb navigation with links to each section.",label:"Breadcrumb Navigation"},{prompt:"A card layout for a team section with member photos, names, and roles.",label:"Team Section"},{prompt:"A chat interface with a list of messages, an input field, and a send button.",label:"Chat Interface"},{prompt:"An FAQ section with questions and collapsible answers.",label:"FAQ Section"},{prompt:"A progress bar indicating completion percentage.",label:"Progress Bar"},{prompt:"A weather widget displaying the current temperature, weather condition, and a weekly forecast.",label:"Weather Widget"},{prompt:"A timeline component showing events in chronological order with dates and descriptions.",label:"Timeline"},{prompt:"A responsive navigation bar with dropdown menus and a search input.",label:"Navigation Bar"},{prompt:"A statistics dashboard with cards displaying key metrics, charts, and graphs.",label:"Statistics Dashboard"},{prompt:"An image gallery with thumbnails and a lightbox feature for viewing full-size images.",label:"Image Gallery"},{prompt:"A testimonials carousel with customer reviews, names, and photos.",label:"Testimonials Carousel"},{prompt:"A live chat widget with an input field, send button, and conversation history.",label:"Live Chat Widget"},{prompt:"An order summary card with product details, prices, and a total amount.",label:"Order Summary"},{prompt:"A loading spinner or progress indicator.",label:"Loading Spinner"},{prompt:"A feature section with icons, headings, and descriptions for each feature.",label:"Feature Section"}],prompt:"",promptImages:[],promptButtons:[],isUserScrollBottom:!0}},mounted(){window.ui=this;const e=Math.floor(Math.random()*this.examples.length);this.placeholder=this.examples[e].prompt,this.promptButtons=this.getRandomPrompts(this.examples,5)},methods:{changeImages(e){this.images=e},shuffle(){this.promptButtons=this.getRandomPrompts(this.examples,5)},getRandomPrompts(e,t){return e.sort(()=>.5-Math.random()).slice(0,t)},examplePrompt(e){this.prompt=e.prompt},send:async function(){this.state="code";let e=this.getPrompt;this.code!=""&&e.unshift([{type:"text",text:this.code}]),console.log(e,"PROMPT");try{const t=await axios.post(route("ui.send",{}),{prompt:e}),p=t.data.id;alert(t.data.id);const n=new EventSource(route("ui.stream",{uiId:p}),{withCredentials:!0});this.code="",n.addEventListener("message",i=>{const m=JSON.parse(i.data);m.delta.content&&(this.code=this.code+m.delta.content)}),n.addEventListener("stop",i=>{n.close()}),n.addEventListener("error",i=>{console.error("EventSource failed:",i.data),n.close()})}catch(t){console.error("Error with POST request:",t)}}},computed:{getPrompt(){return[{type:"text",text:this.prompt},...this.promptImages]}}}),$=a("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"},"Ui",-1),z={class:"flex flex-col h-full"},N=a("img",{class:"absolute z-10 inset-0 object-cover pointer-events-none",src:"/img/bg.svg"},null,-1),V={class:"@container z-20 items-center justify-center grow flex flex-col"},U={class:"z-20 my-32"},j={class:"max-w-[80vw] overflow-x-scroll flex space-x-2 mt-2 whitespace-nowrap"},O=["onClick"],R=a("svg",{xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24","stroke-width":"1.5",stroke:"currentColor",class:"size-6"},[a("path",{"stroke-linecap":"round","stroke-linejoin":"round",d:"M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"})],-1),q=[R],D={class:"relative"},H={key:0,class:"relative z-10"},W={class:"grid grid-cols-3 gap-4 px-4 z-20 relative"},Q=["onClick"],G={class:"bg-gray-100 rounded-xl"},J={key:0},K=["src"],Z={key:1,class:"relative z-10"};function X(e,t,p,n,i,m){const f=d("Head"),w=d("PromptForm"),v=d("Editor"),A=d("AuthenticatedLayout");return s(),r(u,null,[l(f,{title:"Chat"}),l(A,{class:"bg-gray-50 dark:bg-black"},{header:h(()=>[$]),default:h(()=>[a("div",z,[N,a("div",V,[a("div",U,[l(w,{class:"",onSend:e.send,onChangeImages:e.changeImages,images:e.promptImages,prompt:e.prompt,"onUpdate:prompt":t[0]||(t[0]=o=>e.prompt=o),rows:"2",placeholder:e.placeholder},null,8,["onSend","onChangeImages","images","prompt","placeholder"]),a("div",j,[(s(!0),r(u,null,g(e.promptButtons,o=>(s(),r("button",{class:x([e.prompt==o.prompt?"bg-gray-100":"bg-white","border hover:border-gray-800 rounded-full px-2"]),onClick:c=>e.examplePrompt(o)},[a("div",null,b(o.label),1)],10,O))),256)),a("button",{class:"border hover:border-gray-800 rounded-full px-2 bg-white",onClick:t[1]||(t[1]=(...o)=>e.shuffle&&e.shuffle(...o))},q)])])]),a("div",D,[a("button",{onClick:t[2]||(t[2]=o=>e.state="list")},"List"),a("button",{onClick:t[3]||(t[3]=o=>e.state="code")},"Code"),l(P,{duration:{enter:500,leave:800},name:"fade"},{default:h(()=>[e.state=="list"?(s(),r("div",H,[a("div",W,[(s(!0),r(u,null,g(e.components,o=>{var c;return s(),r("div",{onClick:Y=>e.$router.visit(e.route("ui.edit",{uiId:o.id})),class:"shadow-xl border bg-white h-40"},[a("div",G,[S(b(o.prompt[0].text)+" ",1),((c=o.prompt[1])==null?void 0:c.type)=="image_url"?(s(),r("div",J,[a("img",{class:"w-10",src:o.prompt[1].image_url.url},null,8,K)])):B("",!0)])],8,Q)}),256))])])):(s(),r("div",Z,[l(v,{class:"border-2 mx-5 lg:mx-20",modelValue:e.code,"onUpdate:modelValue":t[4]||(t[4]=o=>e.code=o)},null,8,["modelValue"])]))]),_:1})])])]),_:1})],64)}const ie=M(T,[["render",X]]);export{ie as default};