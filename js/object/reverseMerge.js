Object.prototype.reverseMerge = function(source) {
    for(var key in source) {
        if(source.hasOwnProperty(key) && !(key in this)) {
            this[key] = source[key];
        }
    }

    return this;
};

/** alternative
Object.prototype.reverseMerge = function(source) {
    source.each(function(key, value) {
        if(!(key in this)) {
            this[key] = value;
        }
    }, this); // context argument to ensure that `this` references correctly

    return this;
};
*/
