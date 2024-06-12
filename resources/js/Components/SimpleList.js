"use strict"

import {reactive} from "vue";
import _ from "lodash";

export function SimpleList(vueComponent, options) {

    options = _.extend({
      search: ['name'],
    }, options);

    let that = this;
    that.filter = "";
    that.sort = null;
    that.items = reactive([]);

    let checkSearch = function(f, item){
        for (const field of options.search) {
            if(_.isString(item[field]) && item[field].toLowerCase().indexOf(f) !== -1){
                return false;
            }
        }
        return true;
    }

    that.refresh = function () {
        let f = that.filter.trim().toLowerCase();
        that.items.length = 0;
        vueComponent.items
            .filter((item) => {
                if (item.deleted) return false;
                if (f !== '') {
                    if (checkSearch(f, item)) {
                        return false;
                    }
                }
                return true;
            })
            ./*sort((itm1, itm2) => {
                let desc = 1;
                if (that.sort !== null) {
                    if (that.sort.dir == 'desc') {
                        desc = -1;
                    }
                    let res = 0;
                    if(that.sort.strategy == 'numeric'){
                        res = itm2[that.sort.name] - itm1[that.sort.name];
                    } else {
                        res = itm1[that.sort.name].localeCompare(itm2[that.sort.name]);
                    }
                    if (res !== 0) {
                        return res * desc;
                    }
                }
                return (itm1.id - itm2.id) * desc;
            }).*/map(function (itm) {
                that.items.push(itm);
            });
    };

    that.init = function () {
        vueComponent.$watch('lItems.filter', that.refresh);
        vueComponent.$watch('lItems.sort', that.refresh);
        vueComponent.$watch('items', {
            deep: true,
            handler: that.refresh
        });
        that.refresh();
    };

}
