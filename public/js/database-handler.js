function CustomDatabase(name, options) {
    if(!(this instanceof CustomDatabase)) {
        return new CustomDatabase(name, options);
    }

    this.request = undefined;
    this.db_object = "TzCoSystemDatabase";
    this.db_name = (name)? name: "SystemDatabase";
    this.options = options;
    if(!window[this.db_object]) window[this.db_object] = {}
    
    return this;
}

CustomDatabase.prototype.init = function(options) {
    window.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
    this.db_version = (options && options.version)? options.version: 1;
    this.tables = (options && Array.isArray(options.tables))? options.tables: [];

    if(window.indexedDB) {
        this.request = window.indexedDB.open(this.db_name, this.db_version);
        this.request.onupgradeneeded = this.onupgradeneeded();
        this.request.onsuccess = this.onsuccess();
        this.request.onerror = this.onerror();
    }
}

CustomDatabase.prototype.onupgradeneeded = function() {
    var $this = this;
    return function(e) {
        var db = e.target.result;
        for (const table of $this.tables) {
            var store = db.createObjectStore(table.name, {keyPath: table.key});

            if(Array.isArray(table.indexes)) {
                for (const ind of table.indexes) {
                    store.createIndex(ind.name, ind.key, {unique: false});
                }
            }
        }
    }
}

CustomDatabase.prototype.onsuccess = function() {
    var $this = this;
    return function(e) {
        $this.db = e.target.result;
    }
}

CustomDatabase.prototype.onerror = function() {
    return function(e) {
        console.log("There was as error: "+ e.target.errorCode);
    }
}

CustomDatabase.prototype.table = function(name) {
    var $parent = this;

    function Query(name) {
        if(!(this instanceof Query)) {
            return new Query(options);
        }

        this.table = name;

        return this;
    }

    Query.prototype.db = function() {
        $parent.init($parent.options);
        return new Promise(function(resolve, reject){
            var i = 0;
            var timer = setInterval(function(){
                if($parent.db) { resolve($parent.db); clearInterval(timer); }
                if(i > 100) { resolve(null); clearInterval(timer); }

                i++;
            }, 500);
        })
    }

    Query.prototype.get = async function(){
        var db = await this.db();
        var tx = db.transaction(this.table, "readwrite");
        var store = tx.objectStore(this.table);

        tx.oncomplete = function(){ db.close() }
        return await (new Promise(function(resolve, reject) {
            store.getAll().onsuccess = function(event) {
                resolve(event.target.result);
            };
        }))
        
    }

    Query.prototype.row = async function(id){
        var db = await this.db();
        var tx = db.transaction(this.table, "readwrite");
        var store = tx.objectStore(this.table);

        tx.oncomplete = function(){ db.close() }
        return await (new Promise(function(resolve, reject) {
            store.get(id).onsuccess = function(event) {
                resolve(event.target.result);
            };
        }))
    }


    Query.prototype.find = async function() {
        return await this.get();
    }

    Query.prototype.findOne = async function(id) {
        return await this.row(id);
    }

    Query.prototype.create = async function(data){
        var db = await this.db();
        
        var tx = db.transaction(this.table, "readwrite");
        var store = tx.objectStore(this.table);

        store.put(data);
        tx.oncomplete = function(){ db.close() }

        return {status: "success"};
    }

    Query.prototype.update = async function(data){
        return this.create(data);
    }

    Query.prototype.delete = async function(id){
        var db = await this.db();
        var tx = db.transaction(this.table, "readwrite");
        var store = tx.objectStore(this.table);

        tx.oncomplete = function(){ db.close() }
        store.delete(id);

        return {status: "success"};
    }

    Query.prototype.clear = async function(){
        var db = await this.db();
        var tx = db.transaction(this.table, "readwrite");
        var store = tx.objectStore(this.table);

        tx.oncomplete = function(){ db.close() }
        store.clear();

        return {status: "success"};
    }

    return new Query(name);
}