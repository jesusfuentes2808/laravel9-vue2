var storage = {
    set(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
        // localStorage.key = value;
        // localStorage[key] = value;
    },
    get(key) {
        return JSON.parse(localStorage.getItem(key));
    },
    getForIndex(index) {
        return localStorage.key(index);
    },
    getKeys(){
        let items = this.getAll();
        let keys = [];
        for (let index=0;index<items.length;index++){
            keys.push(items[index].key);
        }
        return keys;
    },
    getLength() {
        return localStorage.length;
    },
    getSupport() {
        return (typeof (Storage) !== "undefined") ? true : false;
    },
    remove(key) {
        localStorage.removeItem(key);
    },
    removeAll() {
        localStorage.clear();
    },
    getAll() {
        let len = localStorage.length;  // Obtener la longitud
        let arr = new Array(); // Definir el conjunto de datos
        for (var i = 0; i < len; i++) {
            // Obtener el índice de la clave a partir de 0
            var getKey = localStorage.key(i);
            // Obtiene el valor correspondiente a la clave
            var getVal = localStorage.getItem(getKey);
            // poner en la matriz
            arr[i] = {
                'key': getKey,
                'val': getVal,
            }
        }
        return arr;
    }
}

export default storage;
