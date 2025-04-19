import{r as u,c as N,n as b,o as O,a as c,b as l,d as s,w as _,v as T,u as o,i as w,F as x,e as S,t as k,f as U,g as B,_ as F,h as D,j as M,k as j,l as z}from"./app-BhctBKvf.js";import{a as g}from"./adminToolService-Cduooc0c.js";function A(){const r=u(""),n=u(!1),f=u(""),C=[{label:"Clear Cache",command:"cache:clear"},{label:"Clear Route Cache",command:"route:clear"},{label:"Route List",command:"route:list"},{label:"Clear Config Cache",command:"config:clear"},{label:"Clear View Cache",command:"view:clear"},{label:"Migrate Fresh & Seed",command:"migrate:fresh --seed"}],h=u([]),y=u([]),p=u(""),d=u(10),i=u(""),v=N(()=>{const a=String(r.value).toLowerCase();return a.includes("error")||a.includes("exception")?"bg-mulberry-purple text-torch-red":"bg-badge-active text-color-active"});return{result:r,isLoading:n,commands:C,preClass:v,factories:h,seeders:y,selectedFactory:p,factoryCount:d,selectedSeeder:i,runCommand:async a=>{var t,e;n.value=!0,f.value=a,r.value="",await b();try{const{data:m}=await g.adminLaravelCommand({command:a});r.value=`${m.message}

Output:
${m.data}`}catch(m){r.value=`Error: ${((e=(t=m.response)==null?void 0:t.data)==null?void 0:e.error)||m.message}`}finally{n.value=!1,f.value=""}},fetchFactories:async()=>{try{const{data:a}=await g.getFactories();h.value=a.data}catch(a){console.error("Error fetching factories:",a)}},fetchSeeders:async()=>{try{const{data:a}=await g.getSeeders();y.value=a.data}catch(a){console.error("Error fetching seeders:",a)}},runFactory:async()=>{var a,t;if(!p.value||d.value<1){alert("Chọn Factory và nhập số lượng hợp lệ!");return}n.value=!0,r.value="",await b();try{const{data:e}=await g.runFactory({factory:p.value,count:d.value});r.value=`${e.message}

Output:
${e.output}`}catch(e){r.value=`Error: ${((t=(a=e.response)==null?void 0:a.data)==null?void 0:t.error)||e.message}`}finally{n.value=!1}},runSeeder:async()=>{var a,t;if(!i.value){alert("Chọn Seeder hợp lệ!");return}n.value=!0,r.value="",await b();try{const{data:e}=await g.runSeeder({seeder:i.value});r.value=`${e.message}

Output:
${e.output}`}catch(e){r.value=`Error: ${((t=(a=e.response)==null?void 0:a.data)==null?void 0:t.error)||e.message}`}finally{n.value=!1}}}}const q={class:"space-y-4"},G={class:"grid grid-cols-12 gap-2"},H={class:"col-span-5"},I=["value"],J={class:"col-span-3"},K={class:"col-span-4"},P={class:"grid grid-cols-12 gap-2"},Q={class:"col-span-8"},W=["value"],X={class:"col-span-4"},Y={class:"grid grid-cols-12 gap-2"},Z={class:"col-span-3 w-full"},ee={class:"col-span-9"},te={class:"p-3 rounded h-[400px] overflow-auto space-y-2 bg-background-body"},ae={key:0,class:"text-button-warning-text font-medium flex items-center gap-2"},se={key:1},ne={__name:"LaravelCommands",setup(r){const{result:n,isLoading:f,commands:C,preClass:h,factories:y,seeders:p,selectedFactory:d,factoryCount:i,selectedSeeder:v,fetchFactories:$,fetchSeeders:V,runCommand:E,runFactory:L,runSeeder:R}=A();return O(async()=>{await $(),await V()}),(a,t)=>(l(),c("div",q,[s("div",G,[s("div",H,[_(s("select",{"onUpdate:modelValue":t[0]||(t[0]=e=>w(d)?d.value=e:null),class:"p-2 rounded w-full"},[t[3]||(t[3]=s("option",{disabled:"",value:""},"Select Factory",-1)),(l(!0),c(x,null,S(o(y),e=>(l(),c("option",{key:e,value:e},k(e),9,I))),128))],512),[[T,o(d)]])]),s("div",J,[_(s("input",{"onUpdate:modelValue":t[1]||(t[1]=e=>w(i)?i.value=e:null),type:"number",min:"1",class:"p-2 rounded w-full"},null,512),[[U,o(i)]])]),s("div",K,[B(F,{onClick:o(L),text:"Run Factory",btnClass:"bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple w-full"},null,8,["onClick"])])]),s("div",P,[s("div",Q,[_(s("select",{"onUpdate:modelValue":t[2]||(t[2]=e=>w(v)?v.value=e:null),class:"p-2 rounded w-full"},[t[4]||(t[4]=s("option",{disabled:"",value:""},"Select Seeder",-1)),(l(!0),c(x,null,S(o(p),e=>(l(),c("option",{key:e,value:e},k(e),9,W))),128))],512),[[T,o(v)]])]),s("div",X,[B(F,{onClick:o(R),text:"Run Seeder",btnClass:"bg-indigo-night text-amethyst-purple rounded-md hover:bg-royal-purple w-full"},null,8,["onClick"])])]),s("div",Y,[s("div",Z,[(l(!0),c(x,null,S(o(C),e=>(l(),D(F,{key:e.command,onClick:m=>o(E)(e.command),btnClass:"w-full bg-indigo-night transition text-amethyst-purple rounded-md hover:bg-royal-purple mb-3",icon:"ti-terminal",text:e.label},null,8,["onClick","text"]))),128))]),s("div",ee,[s("div",te,[o(f)?(l(),c("div",ae,t[5]||(t[5]=[s("i",{class:"ti-reload animate-spin"},null,-1),M(" Running Command.. ")]))):(l(),c("div",se,[t[6]||(t[6]=s("h3",{class:"mb-2 text-button-warning-text font-medium flex items-center gap-2 p-2"},[s("i",{class:"ti-clipboard"}),M(" Execution Result: ")],-1)),o(n)?(l(),c("pre",{key:0,class:z([o(h),"whitespace-pre-wrap text-sm p-2 rounded-md"])},k(o(n)),3)):j("",!0)]))])])])]))}};export{ne as default};
