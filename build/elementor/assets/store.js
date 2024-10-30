class Store{constructor(){this._listeners={},this._store={}}on(e,s){this._listeners[e]||(this._listeners[e]=[]),this._listeners[e].includes(s)||this._listeners[e].push(s)}off(e,s){this._listeners[e]&&(this._listeners[e]=this._listeners[e].filter((e=>e!==s)))}emit(e,...s){this._listeners[e]&&this._listeners[e].forEach((e=>{e(...s)}))}create(e,s){return this._store[e]=s,this.emit(`change-${e}`,s),{get:()=>this.get(e),set:s=>this.set(e,s),subscribe:s=>{this.on(`change-${e}`,s)},unsubscribe:s=>{this.removeListener(`change-${e}`,s)},key:e}}get(e){return this._store[e]}set(e,s){"function"==typeof s&&(s=s(this._store[e])),s instanceof Promise?s.then((s=>{this._store[e]=s,this.emit(`change-${e}`,s)})):(this._store[e]=s,this.emit(`change-${e}`,s))}}const store=new Store,handleStoreCheck=e=>new Promise((s=>{const t=store.get(e);t?s(t):store.on(`change-${e}`,s)}));