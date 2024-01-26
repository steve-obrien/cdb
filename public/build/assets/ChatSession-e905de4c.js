import _ from"./ChatLayout-2cabb583.js";import{p as k,Z as S,k as T,x as p,q as c,o as i,f as l,a as d,w as x,F as u,b as o,s as f,t as g}from"./app-18d35bfb.js";import y from"./PromptForm-94426f5c.js";import $ from"./ChatMessage-0f323182.js";import{_ as E}from"./_plugin-vue_export-helper-c27b6911.js";import"./AuthenticatedLayout-b12078d5.js";const I=k({props:{user:Object,chats:Array,sessions:Array,sessionId:{required:!0,type:String}},components:{Head:S,Link:T,ChatLayout:_,PromptForm:y,ChatMessage:$},data(){return{prompt:"",data:[],messages:[],charsPerToken:4,tokenCostPerThousand:.01,isUserScrollBottom:!0,channel:null,channelTeam:null,users:{}}},unmounted(){window.Echo.leave(`chat.${this.sessionId}`)},mounted(){window.chat=this,this.messages=this.chats,p(()=>{this.$refs.chatWindow.scrollTo(0,this.$refs.chatWindow.scrollHeight)});const t=window.Echo.join(`chat.${this.sessionId}`).here(s=>{console.log(s,"chat session channel"),s.forEach(e=>{this.users[e.id]=e})}).joining(s=>{console.log("joining",s.name),this.users[s.id]=s}).leaving(s=>{this.$page.props.auth.user.id!==s.id&&delete this.users[s.id]}).error(s=>{console.error(s)});t.listen("ChatMessage",s=>{this.addChatChunk(s.message),console.log(s)}),t.listen("ChatMessageChunk",s=>{this.addChatChunk(s.message),console.log(s)})},computed:{messageCosts(){let t="";return this.messages.map(s=>{t+=s.content;let e=t.length;s.role=="assistant"&&(e=s.content.length);const n=e/this.charsPerToken,a=n/1e3*this.tokenCostPerThousand,h=s.content.length/this.charsPerToken,m=s.content.length;return{role:s.role,length:e,messageLength:m,tokens:n,cost:a,mesageTokens:h}})},total(){const s=this.messageCosts.reduce((n,a)=>n+a.length,0)/this.charsPerToken,e=s/1e3*this.tokenCostPerThousand;return{tokens:s,cost:e.toFixed(4)}}},methods:{addChatChunk(t){if(t.user_id==this.$page.props.auth.user.id)return;const s=this.messages.findIndex(e=>e.id===t.id);s===-1?this.messages.push(t):this.messages[s].content=t.content,p(()=>{this.scrollToBottom()})},test(){window.app.channelTeam.whisper("whisper",{msg:"hello?"})},handleScroll(){console.log("USER SCROLL");let t=this.$refs.chatWindow;this.isUserScrollBottom=t.scrollTop+t.clientHeight>=t.scrollHeight-40},scrollToBottom(){let t=this.$refs.chatWindow;this.isUserScrollBottom&&t.scrollTo(0,t.scrollHeight)},send:async function(t){const s=t.prompt,e=await axios.post(route("api.chatStart"),{sessionId:this.sessionId,prompt:s});e.status!=200&&alert("error!"),console.log(e.data,"response.data"),e.data.sessionId!==this.sessionId&&alert("session ids do not match!?");const n=e.data.chat;this.messages.push(n),p(()=>{this.$refs.chatWindow.scrollTo(0,this.$refs.chatWindow.scrollHeight)}),this.prompt="",this.streamResponse(this.sessionId)},streamResponse(t){try{let s={role:"assistant",content:"",state:"loading"};this.messages.push(s);let e=this.messages.indexOf(s);const n=new EventSource(route("api.chatStream",t),{withCredentials:!0});n.addEventListener("message",a=>{console.log("STREAM",a.data),this.messages[e].state="streaming";const h=JSON.parse(a.data);h.delta.content&&(this.messages[e].content=s.content+h.delta.content,this.scrollToBottom())}),n.addEventListener("stop",a=>{n.close(),this.messages[e].state="finished"}),n.addEventListener("error",a=>{console.error("EventSource failed:",a)})}catch(s){console.error("There was a problem with the streaming operation:",s)}}}});const L={class:"grow flex relative"},P={class:"px-4 py-8 flex flex-col flex-1 text-base mx-auto gap-5 @md:max-w-3xl @lg:max-w-[40rem] @xl:max-w-[48rem] group final-completion"},b={key:0},B=o("div",{class:"flex"},[o("div",{class:"h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white"},"AI"),o("div",null,[o("div",{class:"mt-1 font-semibold"},"ChatGPT"),o("div",{class:"prose"},"How can I help you today?")])],-1),H=[B],W={class:"px-4"},F={class:"text-center"},M={class:"text-xs"},U={class:"flex"},j=["src"];function R(t,s,e,n,a,h){const m=c("Head"),w=c("ChatMessage"),C=c("PromptForm"),v=c("ChatLayout");return i(),l(u,null,[d(m,{title:"Chat"}),d(v,{sessions:t.sessions},{default:x(()=>[o("div",L,[o("div",{ref:"chatWindow",class:"absolute inset-0 overflow-y-scroll",onScroll:s[0]||(s[0]=(...r)=>t.handleScroll&&t.handleScroll(...r))},[o("div",P,[t.messages.length==0?(i(),l("div",b,H)):(i(!0),l(u,{key:1},f(t.messages,(r,A)=>(i(),l("div",null,[d(w,{message:r},null,8,["message"])]))),256))])],544)]),o("div",W,[d(C,{onSend:t.send,prompt:t.prompt,"onUpdate:prompt":s[1]||(s[1]=r=>t.prompt=r)},null,8,["onSend","prompt"]),o("div",F,[o("code",M,"tokens: "+g(t.total.tokens)+" / cost: "+g(t.total.cost)+" - scroll: "+g(t.isUserScrollBottom),1)]),o("div",U,[o("button",{onClick:s[2]||(s[2]=(...r)=>t.test&&t.test(...r))},"TEST"),(i(!0),l(u,null,f(t.users,r=>(i(),l("div",{key:r.id},[o("img",{class:"h-4 w-4 rounded-full bg-gray-50",src:r.avatar_url,alt:"Dave Zulauf"},null,8,j)]))),128))])])]),_:1},8,["sessions"])],64)}const G=E(I,[["render",R]]);export{G as default};
