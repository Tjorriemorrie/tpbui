var app = angular.module('app', ['ngRoute']);

window.indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
window.IDBTransaction = window.IDBTransaction || window.webkitIDBTransaction || window.msIDBTransaction;
window.IDBKeyRange = window.IDBKeyRange || window.webkitIDBKeyRange || window.msIDBKeyRange;

var version = 1;
var loadFixtures = false;
var openRequest = indexedDB.open('tpbui', version);

openRequest.onupgradeneeded = function(e) {
    console.warn('idb onupgradeneeded', version);
    var db = e.target.result;
    setUpStores(db);
};

openRequest.onsuccess = function(e) {
    var db = e.target.result;
    app.constant('DB', db);
    console.info('idb onsuccess', db);
    if (loadFixtures) {
        loadCategoryFixtures(db);
    } else {
        bootstrap();
    }
};

openRequest.onerror = function(e) {
    console.dir(e.target.error);
    console.error('idb onerror', e.target.error.message);
};

function bootstrap() {
    console.info('bootstrap');
    angular.element(document).ready(function() {
        angular.bootstrap(document, ['app']);
    });
}

function setUpStores(db) {
    var storeTorrent = db.createObjectStore('torrent', { keyPath: 'id' });
    storeTorrent.createIndex('category', 'category', { unique: false });
    console.info('set up torrent', storeTorrent);

    var storeCategory = db.createObjectStore('category', { keyPath: 'code' });
    console.info('set up category', storeCategory);
    loadFixtures = true;
}

function loadCategoryFixtures(db) {
    console.info('load category fixtures');
    var total = fixtureCategories.length;
    var store = db.transaction('category', 'readwrite').objectStore('category');
    for (var i in fixtureCategories) {
        store.add(fixtureCategories[i]).onsuccess = function(event) {
            //console.info('added', total, fixtureCategories[i]);
            total--;
            if (total < 1) {
                bootstrap();
            }
        };
    }
}

var fixtureCategories = [
    {code: 100, name: 'Audio', parent: null},
    {code: 101, name: 'Music', parent: 100},
    {code: 102, name: 'Audiobooks', parent: 100},
    {code: 103, name: 'Sound Clips', parent: 100},
    {code: 104, name: 'FLAC', parent: 100},
    {code: 199, name: 'Other', parent: 100},

    {code: 200, name: 'Video', parent: null},
    {code: 201, name: 'Movies', parent: 200},
    {code: 202, name: 'Movies DVDR', parent: 200},
    {code: 203, name: 'Music Videos', parent: 200},
    {code: 204, name: 'Movie Clips', parent: 200},
    {code: 205, name: 'TV Shows', parent: 200},
    {code: 206, name: 'Handheld', parent: 200},
    {code: 207, name: 'HD Movies', parent: 200},
    {code: 208, name: 'HD TV Shows', parent: 200},
    {code: 209, name: '3D', parent: 200},
    {code: 299, name: 'Other', parent: 200},

    {code: 300, name: 'Software', parent: null},
    {code: 301, name: 'Windows', parent: 300},
    {code: 302, name: 'Mac', parent: 300},
    {code: 303, name: 'Unix', parent: 300},
    {code: 304, name: 'Handheld', parent: 300},
    {code: 305, name: 'iOS (iPad/iPhone)', parent: 300},
    {code: 306, name: 'Android', parent: 300},
    {code: 399, name: 'Other', parent: 300},

    {code: 400, name: 'Games', parent: null},
    {code: 401, name: 'PC', parent: 400},
    {code: 402, name: 'Mac', parent: 400},
    {code: 403, name: 'PSx', parent: 400},
    {code: 404, name: 'Xbox360', parent: 400},
    {code: 405, name: 'Wii', parent: 400},
    {code: 406, name: 'Handheld', parent: 400},
    {code: 407, name: 'iOS (iPad/iPhone)', parent: 400},
    {code: 408, name: 'Android', parent: 400},
    {code: 499, name: 'Other', parent: 400},

    {code: 600, name: 'Other', parent: null},
    {code: 601, name: 'eBooks', parent: 600},
    {code: 602, name: 'Comics', parent: 600},
    {code: 603, name: 'Pictures', parent: 600},
    {code: 604, name: 'Covers', parent: 600},
    {code: 605, name: 'Physibles', parent: 600},
    {code: 699, name: 'Other', parent: 600}
];
