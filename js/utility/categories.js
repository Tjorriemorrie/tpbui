angular.module('app').factory('categories', function($filter) {
    var categories = {
        groups: [
            {
                code: 100,
                name: 'Audio',
                children: [
                    {code: 101, name: 'Music'},
                    {code: 102, name: 'Audiobooks'},
                    {code: 103, name: 'Sound Clips'},
                    {code: 104, name: 'FLAC'},
                    {code: 199, name: 'Other'}
                ]
            },
            {
                code: 200,
                name: 'Video',
                children: [
                    {code: 201, name: 'Movies'},
                    {code: 202, name: 'Movies DVDR'},
                    {code: 203, name: 'Music Videos'},
                    {code: 204, name: 'Movie Clips'},
                    {code: 205, name: 'TV Shows'},
                    {code: 206, name: 'Handheld'},
                    {code: 207, name: 'HD Movies'},
                    {code: 208, name: 'HD TV Shows'},
                    {code: 209, name: '3D'},
                    {code: 299, name: 'Other'}
                ]
            },
            {
                code: 300,
                name: 'Software',
                children: [
                    {code: 301, name: 'Windows'},
                    {code: 302, name: 'Mac'},
                    {code: 303, name: 'Unix'},
                    {code: 304, name: 'Handheld'},
                    {code: 305, name: 'iOS (iPad/iPhone)'},
                    {code: 306, name: 'Android'},
                    {code: 399, name: 'Other'}
                ]
            },
            {
                code: 400,
                name: 'Games',
                children: [
                    {code: 401, name: 'PC'},
                    {code: 402, name: 'Mac'},
                    {code: 403, name: 'PSx'},
                    {code: 404, name: 'Xbox360'},
                    {code: 405, name: 'Wii)'},
                    {code: 406, name: 'Handheld'},
                    {code: 407, name: 'iOS (iPad/iPhone)'},
                    {code: 408, name: 'Android'},
                    {code: 499, name: 'Other'}
                ]
            },
            {
                code: 600,
                name: 'Other',
                children: [
                    {code: 601, name: 'eBooks'},
                    {code: 602, name: 'Comics'},
                    {code: 603, name: 'Pictures'},
                    {code: 604, name: 'Covers'},
                    {code: 605, name: 'Physibles'},
                    {code: 699, name: 'Other'}
                ]
            }
        ],

        find: function(code) {
            var res = null;
            angular.forEach(categories.groups, function(group) {
                angular.forEach(group.children, function(item) {
                    if (item.code == code) {
                        res = item;
                    }
                })
            });
            return res;
        }
    };
    return categories;
});
