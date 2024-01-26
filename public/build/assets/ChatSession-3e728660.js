import v from"./ChatLayout-0719687f.js";import{p as _,Z as S,k as T,x as m,q as h,o as i,f as r,a as c,w as x,F as p,b as o,s as f,t as u}from"./app-afe1df0b.js";import y from"./PromptForm-640d0e94.js";import $ from"./ChatMessage-578c5847.js";import{_ as I}from"./_plugin-vue_export-helper-c27b6911.js";import"./AuthenticatedLayout-9c0ef989.js";const P=_({props:{user:Object,chats:Array,sessions:Array,sessionId:{required:!0,type:String}},components:{Head:S,Link:T,ChatLayout:v,PromptForm:y,ChatMessage:$},data(){return{prompt:"",data:[],messages:[],charsPerToken:4,tokenCostPerThousand:.01,isUserScrollBottom:!0,channel:null,channelTeam:null,users:{}}},unmounted(){window.Echo.leave(`chat.${this.sessionId}`)},mounted(){window.chat=this,this.messages=this.chats,m(()=>{this.$refs.chatWindow.scrollTo(0,this.$refs.chatWindow.scrollHeight)});const t=window.Echo.join(`chat.${this.sessionId}`).here(s=>{console.log(s,"chat session channel"),s.forEach(e=>{this.users[e.id]=e})}).joining(s=>{console.log("joining",s.name),this.users[s.id]=s}).leaving(s=>{this.$page.props.auth.user.id!==s.id&&delete this.users[s.id]}).error(s=>{console.error(s)});t.listen("ChatMessage",s=>{this.addChatChunk(s.message),console.log(s)}),t.listen("ChatMessageChunk",s=>{this.addChatChunk(s.message),console.log(s)})},computed:{messageCosts(){let t="";return this.messages.map(s=>{t+=s.content;let e=t.length;s.role=="assistant"&&(e=s.content.length);const a=e/this.charsPerToken,l=a/1e3*this.tokenCostPerThousand,g=s.content.length/this.charsPerToken,d=s.content.length;return{role:s.role,length:e,messageLength:d,tokens:a,cost:l,mesageTokens:g}})},total(){const s=this.messageCosts.reduce((a,l)=>a+l.length,0)/this.charsPerToken,e=s/1e3*this.tokenCostPerThousand;return{tokens:s,cost:e.toFixed(4)}}},methods:{addChatChunk(t){this.addMessage(t),m(()=>{this.scrollToBottom()})},addMessage(t){const s=this.messages.findIndex(e=>e.id===t.id);s===-1?this.messages.push(t):this.messages[s].content=t.content},test(){window.app.channelTeam.whisper("whisper",{msg:"hello?"})},handleScroll(){console.log("USER SCROLL");let t=this.$refs.chatWindow;this.isUserScrollBottom=t.scrollTop+t.clientHeight>=t.scrollHeight-40},scrollToBottom(){let t=this.$refs.chatWindow;this.isUserScrollBottom&&t.scrollTo(0,t.scrollHeight)},send:async function(t){const s=t.prompt,e=await axios.post(route("api.chatStart"),{sessionId:this.sessionId,prompt:s});e.status!=200&&alert("error!"),console.log(e.data,"response.data"),e.data.sessionId!==this.sessionId&&alert("session ids do not match!?");const a=e.data.chat;this.addMessage(a),m(()=>{this.$refs.chatWindow.scrollTo(0,this.$refs.chatWindow.scrollHeight)}),this.prompt="",this.streamResponse(this.sessionId)},streamResponse(t){try{let s={role:"assistant",content:"",state:"loading"};this.messages.push(s);let e=this.messages.indexOf(s);const a=new EventSource(route("api.chatStream",t),{withCredentials:!0})}catch(s){console.error("There was a problem with the streaming operation:",s)}}}});const b={class:"grow flex relative"},B={class:"px-4 py-8 flex flex-col flex-1 text-base mx-auto gap-5 @md:max-w-3xl @lg:max-w-[40rem] @xl:max-w-[48rem] group final-completion"},H={key:0},W=o("div",{class:"flex"},[o("div",{class:"h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white"},"AI"),o("div",null,[o("div",{class:"mt-1 font-semibold"},"ChatGPT"),o("div",{class:"prose"},"How can I help you today?")])],-1),L=[W],M={class:"px-4"},E={class:"text-center"},F={class:"text-xs"},U={class:"flex"},j=["src"];function R(t,s,e,a,l,g){const d=h("Head"),w=h("ChatMessage"),C=h("PromptForm"),k=h("ChatLayout");return i(),r(p,null,[c(d,{title:"Chat"}),c(k,{sessions:t.sessions},{default:x(()=>[o("div",b,[o("div",{ref:"chatWindow",class:"absolute inset-0 overflow-y-scroll",onScroll:s[0]||(s[0]=(...n)=>t.handleScroll&&t.handleScroll(...n))},[o("div",B,[t.messages.length==0?(i(),r("div",H,L)):(i(!0),r(p,{key:1},f(t.messages,(n,A)=>(i(),r("div",null,[c(w,{message:n},null,8,["message"])]))),256))])],544)]),o("div",M,[c(C,{onSend:t.send,prompt:t.prompt,"onUpdate:prompt":s[1]||(s[1]=n=>t.prompt=n)},null,8,["onSend","prompt"]),o("div",E,[o("code",F,"tokens: "+u(t.total.tokens)+" / cost: "+u(t.total.cost)+" - scroll: "+u(t.isUserScrollBottom),1)]),o("div",U,[o("button",{onClick:s[2]||(s[2]=(...n)=>t.test&&t.test(...n))},"TEST"),(i(!0),r(p,null,f(t.users,n=>(i(),r("div",{key:n.id},[o("img",{class:"h-4 w-4 rounded-full bg-gray-50",src:n.avatar_url,alt:"Dave Zulauf"},null,8,j)]))),128))])])]),_:1},8,["sessions"])],64)}const G=I(P,[["render",R]]);export{G as default};