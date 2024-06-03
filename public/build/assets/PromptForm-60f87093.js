import{l as s,o as a,f as l,b as r,e as d,z as i}from"./app-1ca54d9f.js";import{_ as n}from"./_plugin-vue_export-helper-c27b6911.js";const p=s({emits:["send","update:prompt"],props:["prompt"],mounted(){this.$refs.prompt.focus()},methods:{send(){this.$emit("send",{prompt:this.prompt}),setTimeout(()=>{this.autoExpand()},10)},autoExpand(){this.$refs.prompt.style.height="auto";let e=this.$refs.prompt.scrollHeight+"px";this.$refs.prompt.style.height=e},handleKeyPress(e){(e.ctrlKey||e.metaKey)&&e.key==="Enter"&&this.send()},onBlur(){/^\s*$/.test(this.prompt)&&(this.$refs.prompt.style.height="auto")}}}),h={class:"relative flex h-full flex-1 items-stretch md:flex-col"},m={class:"flex w-full items-center"},u={class:"overflow-hidden [&:has(textarea:focus)]:border-token-border-xheavy [&:has(textarea:focus)]:shadow-[0_2px_6px_rgba(0,0,0,.05)] flex flex-col w-full dark:border-token-border-heavy flex-grow relative border border-token-border-heavy dark:text-white rounded-2xl bg-white dark:bg-gray-800 shadow-[0_0_0_2px_rgba(255,255,255,0.95)] dark:shadow-[0_0_0_2px_rgba(52,53,65,0.95)]"},b=["value"],f=i('<div class="absolute bottom-2 md:bottom-3 left-2 md:left-4"><div class="flex"><button class="btn relative p-0 text-black dark:text-white" aria-label="Attach files"><div class="flex w-full gap-2 items-center justify-center"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M9 7C9 4.23858 11.2386 2 14 2C16.7614 2 19 4.23858 19 7V15C19 18.866 15.866 22 12 22C8.13401 22 5 18.866 5 15V9C5 8.44772 5.44772 8 6 8C6.55228 8 7 8.44772 7 9V15C7 17.7614 9.23858 20 12 20C14.7614 20 17 17.7614 17 15V7C17 5.34315 15.6569 4 14 4C12.3431 4 11 5.34315 11 7V15C11 15.5523 11.4477 16 12 16C12.5523 16 13 15.5523 13 15V9C13 8.44772 13.4477 8 14 8C14.5523 8 15 8.44772 15 9V15C15 16.6569 13.6569 18 12 18C10.3431 18 9 16.6569 9 15V7Z" fill="currentColor"></path></svg></div></button><input multiple="" type="file" tabindex="-1" class="hidden" style="display:none;"></div></div>',1),x=r("span",{class:"","data-state":"closed"},[r("svg",{width:"24",height:"24",viewBox:"0 0 24 24",fill:"none",class:"text-white dark:text-black"},[r("path",{d:"M7 11L12 6L17 11M12 18V7",stroke:"currentColor","stroke-width":"2","stroke-linecap":"round","stroke-linejoin":"round"})])],-1),g=[x];function w(e,t,c,v,k,y){return a(),l("form",{onSubmit:t[5]||(t[5]=d(()=>{},["prevent"])),class:"stretch mx-2 flex flex-row gap-3 last:mb-2 md:mx-4 md:last:mb-6 lg:mx-auto lg:max-w-2xl xl:max-w-3xl"},[r("div",h,[r("div",m,[r("div",u,[r("textarea",{onKeyup:t[0]||(t[0]=(...o)=>e.autoExpand&&e.autoExpand(...o)),onKeydown:t[1]||(t[1]=(...o)=>e.handleKeyPress&&e.handleKeyPress(...o)),onBlur:t[2]||(t[2]=(...o)=>e.onBlur&&e.onBlur(...o)),value:e.prompt,onInput:t[3]||(t[3]=o=>e.$emit("update:prompt",o.target.value)),ref:"prompt",id:"prompt-textarea",tabindex:"0",rows:"1",placeholder:"Message ChatGPT…",class:"m-0 w-full resize-none border-0 bg-transparent py-[10px] pr-10 focus:ring-0 focus-visible:ring-0 dark:bg-transparent md:py-3.5 md:pr-12 placeholder-black/50 dark:placeholder-white/50 pl-10 md:pl-[55px]",style:{"max-height":"250px",height:"52px"}},null,40,b),f,r("button",{onClick:t[4]||(t[4]=(...o)=>e.send&&e.send(...o)),class:"absolute bg-black hover:bg-gray-600 dark:bg-white md:bottom-3 md:right-3 dark:hover:bg-gray-900 dark:disabled:hover:bg-transparent right-2 dark:disabled:bg-white disabled:opacity-10 disabled:text-gray-400 text-white p-0.5 border border-black rounded-lg dark:border-white bottom-1.5 transition-colors","data-testid":"send-button"},g)])])])],32)}const $=n(p,[["render",w]]);export{$ as default};
