import _ from"./ChatLayout-ff0e1df5.js";import{p as T,Z as k,k as S,x as h,q as d,o as r,f as l,a as m,w as x,F as u,b as n,s as f,t as g}from"./app-ccf27683.js";import y from"./PromptForm-9d168840.js";import E from"./ChatMessage-f62c5c02.js";import{_ as $}from"./_plugin-vue_export-helper-c27b6911.js";import"./AuthenticatedLayout-decde868.js";const I=T({props:{user:Object,chats:Array,sessions:Array,sessionId:{required:!0,type:String}},components:{Head:k,Link:S,ChatLayout:_,PromptForm:y,ChatMessage:E},data(){return{prompt:"",data:[],messages:[],charsPerToken:4,tokenCostPerThousand:.01,isUserScrollBottom:!0,channel:null,channelTeam:null,users:{}}},unmounted(){window.Echo.leave(`chat.${this.sessionId}`)},mounted(){window.chat=this,this.messages=this.chats,h(()=>{this.$refs.chatWindow.scrollTo(0,this.$refs.chatWindow.scrollHeight)});const e=window.Echo.join(`chat.${this.sessionId}`).here(s=>{console.log(s,"chat session channel"),s.forEach(t=>{this.users[t.id]=t})}).joining(s=>{console.log("joining",s.name),this.users[s.id]=s}).leaving(s=>{this.$page.props.auth.user.id!==s.id&&delete this.users[s.id]}).error(s=>{console.error(s)});window.Echo.private("team").listen(".ChatSessionCreated",s=>{console.log("EVENT",s)}).listen(".ChatSessionUpdated",s=>{console.log("EVENT",s)}).listenToAll(s=>{console.log("EVENT",s)}),e.listen("ChatMessage",s=>{if(console.log(s),s.message.user_id==this.$page.props.auth.user.id)return;const t=this.messages.findIndex(o=>o.id===s.message.id);t===-1?this.messages.push(s.message):this.messages[t].content=s.message.content,h(()=>{this.scrollToBottom()})}),e.listen("ChatMessageChunk",s=>{if(console.log(s),s.message.user_id==this.$page.props.auth.user.id)return;const t=this.messages.findIndex(o=>o.id===s.message.id);t===-1?this.messages.push(s.message):this.messages[t].content+=s.contentChunk,h(()=>{this.scrollToBottom()})})},computed:{messageCosts(){let e="";return this.messages.map(s=>{e+=s.content;let t=e.length;s.role=="assistant"&&(t=s.content.length);const o=t/this.charsPerToken,i=o/1e3*this.tokenCostPerThousand,c=s.content.length/this.charsPerToken,p=s.content.length;return{role:s.role,length:t,messageLength:p,tokens:o,cost:i,mesageTokens:c}})},total(){const s=this.messageCosts.reduce((o,i)=>o+i.length,0)/this.charsPerToken,t=s/1e3*this.tokenCostPerThousand;return{tokens:s,cost:t.toFixed(4)}}},methods:{test(){window.app.channelTeam.whisper("whisper",{msg:"hello?"})},handleScroll(){console.log("USER SCROLL");let e=this.$refs.chatWindow;this.isUserScrollBottom=e.scrollTop+e.clientHeight>=e.scrollHeight-40},scrollToBottom(){let e=this.$refs.chatWindow;this.isUserScrollBottom&&e.scrollTo(0,e.scrollHeight)},send:async function(e){const s=e.prompt,t=await axios.post(route("api.chatStart"),{sessionId:this.sessionId,prompt:s});t.status!=200&&alert("error!"),console.log(t.data,"response.data"),t.data.sessionId!==this.sessionId&&alert("session ids do not match!?");const o=t.data.chat;this.messages.push(o),h(()=>{this.$refs.chatWindow.scrollTo(0,this.$refs.chatWindow.scrollHeight)}),this.prompt="",this.streamResponse(this.sessionId)},streamResponse(e){try{let s={role:"assistant",content:"",state:"loading"};this.messages.push(s);let t=this.messages.indexOf(s);const o=new EventSource(route("api.chatStream",e),{withCredentials:!0});o.addEventListener("message",i=>{this.messages[t].state="streaming";const c=JSON.parse(i.data);c.delta.content&&(this.messages[t].content=s.content+c.delta.content,this.scrollToBottom())}),o.addEventListener("stop",i=>{o.close(),this.messages[t].state="finished"}),o.addEventListener("error",i=>{alert("error - check console!"),console.error("EventSource failed:",i)})}catch(s){console.error("There was a problem with the streaming operation:",s)}}}});const B={class:"grow flex relative"},L={class:"px-4 py-8 flex flex-col flex-1 text-base mx-auto gap-5 @md:max-w-3xl @lg:max-w-[40rem] @xl:max-w-[48rem] group final-completion"},P={key:0},b=n("div",{class:"flex"},[n("div",{class:"h-8 w-8 min-w-8 mr-2 rounded-full flex items-center justify-center bg-black text-white"},"AI"),n("div",null,[n("div",{class:"mt-1 font-semibold"},"ChatGPT"),n("div",{class:"prose"},"How can I help you today?")])],-1),H=[b],W={class:"px-4"},U={class:"text-center"},F={class:"text-xs"},N={class:"flex"},j=["src"];function M(e,s,t,o,i,c){const p=d("Head"),w=d("ChatMessage"),v=d("PromptForm"),C=d("ChatLayout");return r(),l(u,null,[m(p,{title:"Chat"}),m(C,{sessions:e.sessions},{default:x(()=>[n("div",B,[n("div",{ref:"chatWindow",class:"absolute inset-0 overflow-y-scroll",onScroll:s[0]||(s[0]=(...a)=>e.handleScroll&&e.handleScroll(...a))},[n("div",L,[e.messages.length==0?(r(),l("div",P,H)):(r(!0),l(u,{key:1},f(e.messages,(a,V)=>(r(),l("div",null,[m(w,{message:a},null,8,["message"])]))),256))])],544)]),n("div",W,[m(v,{onSend:e.send,prompt:e.prompt,"onUpdate:prompt":s[1]||(s[1]=a=>e.prompt=a)},null,8,["onSend","prompt"]),n("div",U,[n("code",F,"tokens: "+g(e.total.tokens)+" / cost: "+g(e.total.cost)+" - scroll: "+g(e.isUserScrollBottom),1)]),n("div",N,[n("button",{onClick:s[2]||(s[2]=(...a)=>e.test&&e.test(...a))},"TEST"),(r(!0),l(u,null,f(e.users,a=>(r(),l("div",{key:a.id},[n("img",{class:"h-4 w-4 rounded-full bg-gray-50",src:a.avatar_url,alt:"Dave Zulauf"},null,8,j)]))),128))])])]),_:1},8,["sessions"])],64)}const G=$(I,[["render",M]]);export{G as default};
