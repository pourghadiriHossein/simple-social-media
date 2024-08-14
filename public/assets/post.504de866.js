var g=Object.defineProperty;var m=(o,a,t)=>a in o?g(o,a,{enumerable:!0,configurable:!0,writable:!0,value:t}):o[a]=t;var w=(o,a,t)=>(m(o,typeof a!="symbol"?a+"":a,t),t);import{aE as i}from"./index.97d5dc32.js";class h{static async allPostsForAdmin(a,t){const e=await i.get(`api/post/all-post?page=${a}&filter=${t!=null?t:""}`);if(e.status==200)return e;throw Error("All Posts failed")}static async adminCreatePost(a,t,e){const r=new FormData;r.append("title",a),r.append("description",t),r.append("file",e);const s=await i.post("api/post/create-post",r);if(s.status==200)return s;throw Error("Created Failed")}static async adminUpdatePost(a,t,e,r){const s=new FormData;s.append("title",t),s.append("description",e),s.append("file",r);const p=await i.post(`api/post/update-post/${a}`,s);if(p.status==200)return p;throw Error("Update Failed")}static async adminDeletePost(a){const t=await i.delete(`api/post/delete-post/${a}`);if(t.status==200)return t;throw Error("Delete Failed")}static async allPostInDashboard(a,t){var r,s,p,d,l,c;const e=await i.get(`api/post/all-post?page=${a}&perPage=${t}&filter=`);if(e.status==200){const u=[];return e.data.posts.forEach(n=>{u.push({id:n.id,file:this.serverRoute+n.media[0].url,title:n.title,description:n.description,type:n.media[0].mime_type})}),{posts:u,page_data:{current_page:(p=(s=(r=e.data.meta)==null?void 0:r.pagination)==null?void 0:s.posts)==null?void 0:p.current_page,last_page:(c=(l=(d=e.data.meta)==null?void 0:d.pagination)==null?void 0:l.posts)==null?void 0:c.last_page}}}throw Error("Request Failed")}static async getPostFile(a){const t=await i.get(`api/post/show-file/${a}`);if(t.status==200)return t.data;throw Error("Delete Failed")}}w(h,"serverRoute","http://127.0.0.1:8000/");export{h as P};
