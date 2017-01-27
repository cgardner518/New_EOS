//Set up the data object with everything you need to be reactive. this includes
//pagination, current page, search strings, and the data object for each tab.
//currently, the labcoat_grid object is defined in the view template.

labcoat_grid.pagination = { activeTab: "" };
//labcoat_grid.search = {};
$.each(labcoat_grid.tabs, function (index, value) {
    if (typeof (value.label) === 'undefined') {
        console.error("labcoat_grid[" + index + "] Grid: No grid label specified! Please define in your labcoat_grid configuration.");
    }
    if (typeof (value.itemsPerPage) === 'undefined') {
        labcoat_grid.tabs[index].itemsPerPage = 10;
    }
    if (typeof (value.deleteUrl) === 'undefined') {
        labcoat_grid.tabs[index].deleteUrl = null;
    }
    if (typeof (value.resetPaginationOnSort) === 'undefined') {
        labcoat_grid.tabs[index].resetPaginationOnSort = false;
    }
    if (typeof (value.exclude) === 'undefined') {
        labcoat_grid.tabs[index].exclude = [];
    }
    if (typeof (value.include) === 'undefined') {
        labcoat_grid.tabs[index].include = [];
    }
    if(labcoat_grid.tabs[index].include.length > 0 && labcoat_grid.tabs[index].exclude.length > 0) {
        console.error(labcoat_grid.tabs[index].label + " Grid: Please specify EITHER an array of columns to include OR an array of columns to exclude.");
    }
    if (typeof (value.data) === 'undefined') {
        labcoat_grid.tabs[index].data = [];
    }
    if (typeof (value.loaded) === 'undefined') {
        labcoat_grid.tabs[index].loaded = false;
    }
    if (typeof (value.sortOrder) === 'undefined') {
        labcoat_grid.tabs[index].sortOrder = 1;
    } else {
        switch (value.sortOrder) {
            case "desc":
                labcoat_grid.tabs[index].sortOrder = -1;
                break;
            case "asc":
                labcoat_grid.tabs[index].sortOrder = 1;
                break;
            default:
                labcoat_grid.tabs[index].sortOrder = 1;
                break;
        }
    }
    labcoat_grid.search = "";
    labcoat_grid.selector = ".tab-content td:visible";
    labcoat_grid.pagination[value.label] = {
        itemsPerPage: value.itemsPerPage,
        currentPage: 0,
        search: "",
        totalPages: 0,
        resultCount: 0,
        sortKey: "",
        render: {
            pageNumbers: [],
            ellipses: []
        }
    };
});

Vue.component('anchor-tag', {
    template: "{{{anchorLink}}}",
    props: ['attributes', 'contents'],
    computed: {
        anchorLink: function(){
            if (this.attributes.do_not_link === true) {
                return this.attributes.textContent;
            }
            var link = "<a";
            for (var property in this.attributes) {
                if (this.attributes.hasOwnProperty(property) && property != "textContent") {
                    link += " " + property + '="' + this.attributes[property] + '"';
                }
            }
            link += ">" + this.attributes.textContent + "</a>";
            return link;
        }
    }
});
Vue.component('input-tag', {
    template: "{{{inputField}}}",
    props: ['attributes'],
    computed: {
        inputField: function(){
            var input = "<input";
            for (var property in this.attributes) {
                if (["checked", "disabled", "readonly", "selected", "required", "multiple"].indexOf(property) >= 0 && this.attributes[property] === false) {
                    delete(this.attributes[property]);
                }
                if (this.attributes.hasOwnProperty(property)) {
                    input += " " + property + '="' + this.attributes[property] + '"';
                }
            }
            input += ">";
            return input;
        }
    }
});
Vue.component('grid-element', {
    template: '<td v-if="show"><anchor-tag :attributes="value" v-if="anchor"></anchor-tag><input-tag :attributes="value" v-if="input"></input-tag><span v-if="!anchor && !input">{{ value | json }}</span></td>',
    props: [ 'key', 'value' ],
    computed: {
        show: function () {
            var include = [];
            var exclude = [];
            var pagination = this.$options.parent.$root.pagination;
            $.each(this.$options.parent.$root.tabs, function (index, value) {
                if (value.label == pagination.activeTab) {
                    include = this.include;
                    exclude = this.exclude;
                }
            });
            if (include.length) {
                return include.indexOf(this.key) > -1;
            }
            if (exclude.length) {
                return exclude.indexOf(this.key) == -1;
            }
        },
        anchor: function() {
            return this.key.split('_').pop() == "a";
        },
        input: function() {
            return this.key.split('_').pop() == "input";
        },
    }
});
Vue.component('grid-row', {
    template: '<tr class="{{row.class}}" data-row-id="{{row.id}}"><td is="grid-element" v-for="(key, value) in row " :key="key" :value="value"></td></tr>',
    props: ['row'],
});
Vue.component('grid-loading', {
    props: ['tab'],
    template: '<table v-if="tab.loaded === false"><tbody><tr><td class="text-center">Loading {{ label }}&hellip;<h1><i class="fa fa-spinner fa-spin"></i></h1></td></tr></tbody></table>',
    computed: {
        label: function() {
            return this.tab.label.replace('_', ' ');
        }
    }
});
Vue.component('grid-empty', {
    props: ['tab'],
    template: '<table v-if="tab.loaded === true && tab.data.length == 0"><tbody><tr><td class="text-center">No results found.</td></tr></tbody></table>'
});
Vue.component('grid-results', {
    props: ['results', 'pagination'],
    template: '<grid-loading :tab="results"></grid-loading><grid-empty :tab="results"></grid-empty><table v-show="results.loaded === true"><thead is="grid-header" :header="results.data[0]"></thead><tbody class="grid_results"><tr is="grid-row" v-for="row in results.data | orderBy results.sortKey results.sortOrder | filterBy pagination[pagination.activeTab].search | highlightResults pagination[pagination.activeTab].search | paginate results.label" :row="row"></tr></tbody></table><grid-paginator :pagination="pagination" :tab="results"></grid-paginator>',
    events: {
        columnHeadClick: function () {
            this.sortBy(event);
        }
    },
    filters: {
        //cribbed from https://github.com/vuejs/Discussion/issues/181
        paginate: function (list, tabLabel) {
            //must specify which tab is being processed to prevent crossing the streams
            this.updatePageCount(tabLabel);
            this.pagination[tabLabel].resultCount = list.length;
            if (list.length === 0) {
                return false;
            }

            this.$parent.defineVisiblePageNumbers(tabLabel);

            var index = this.pagination[tabLabel].currentPage * this.pagination[tabLabel].itemsPerPage;
            return list.slice(index, index + this.pagination[tabLabel].itemsPerPage);
        },
        highlightResults: function (arr, search) {
            if (search == "") {
                $(this.$parent.selector).removeHighlight();
            }
            if (search != this.search) {
                this.search = search;
                this.$parent.highlightResults();
            }
            return arr;
        }
    },
    methods: {
        setActiveTab: function (tabLabel) {
            if (this.pagination.activeTab != tabLabel) {
                this.pagination.activeTab = tabLabel;
                this.search = this.pagination[tabLabel].search;
                this.$nextTick(function () {
                    this.$parent.highlightResults();
                });
            }
            this.initializeDeleteButton();
            this.$nextTick(function () {
                this.$parent.highlightResults();
            });
        },
        getActiveIndex: function (tabLabel) {
            for (var index=0, len = this.results.length; index < len; index++) {
                if (this.results[index].label.toLowerCase() == tabLabel.toLowerCase()) {
                    return index;
                }
            }
        },
        sortBy: function (event) {
            //if column isn't intended to be sortable, bail.
            if ($(event.currentTarget).data("column").search("_input") != -1) {
                return false;
            }
            //alter selected column state to indicate new sort order
            if($(event.currentTarget).data("sort-order") == "asc") {
                $(event.currentTarget).data("sort-order", "desc");
            } else {
                $(event.currentTarget).data("sort-order", "asc");
            }
            var index = this.getActiveIndex($(event.currentTarget).closest(".labcoat-grid").data("grid-id"));
            //check config value of resetPaginationOnSort and set page to 1 if true.
            if (this.results.resetPaginationOnSort) {
                this.$parent.setPage(this.results.label, 0);
            }

            this.results.sortKey = $(event.currentTarget).data("column");
            if (this.results.data[0].hasOwnProperty(this.results.sortKey + "_sort")) {
                this.results.sortKey += "_sort";
            }
            if (this.results.data[0][$(event.currentTarget).data("column")].hasOwnProperty("textContent")) {
                this.results.sortKey += ".textContent";
            }
            this.results.sortOrder = $(event.currentTarget).data("sort-order") == "asc" ? 1 : -1;
            this.$root.$options.filters.orderBy(
                this.results,
                $(event.currentTarget).data("column"),
                this.results.sortKey
            );
        },
        updatePageCount: function (tabLabel) {
            var resultCount = this.pagination[tabLabel].resultCount,
                pageCount = Math.ceil(resultCount / this.pagination[tabLabel].itemsPerPage);
            this.pagination[tabLabel].totalPages = pageCount;
            if (this.pagination[tabLabel].currentPage >= pageCount && pageCount > 0) {
                this.$parent.setPage(tabLabel, pageCount - 1, null);
            }
            this.$parent.initializeDeleteButton();
        },
    },
});
Vue.component('sort-icons', {
    props: ['column'],
    template: '<i class="fa fa-fw fa-sort" :class="sortorder" v-if="show"></i>',
    computed: {
        show: function () {
            return this.column.search('_input') == -1;
        },
        sortorder: function () {
            //Determine if the current column is active, then return appropriate fa=* classname.
            var tab = this.getTabObject();
            //normalize tab.sortKey without sub-props
            tab.sortKey = tab.sortKey.replace('.textContent', '');
            //column label is not special and can be approved straight away.
            if (this.column == tab.sortKey) {
                return this.getSortClass(tab);
            }
            //get possible sort column names and swap for this.column for the following comparison.
            var currentColumn = tab.sortKey;
            //remove column modifiers and overwrite currentColumn for comparison.
            if (['a', 'input', 'sort'].indexOf(tab.sortKey.split('_').pop()) >= 0) {
                currentColumn = tab.sortKey.substring(0,tab.sortKey.lastIndexOf('_'));
            }
            //adjusted column labels are ready for comparison.
            if (this.column == currentColumn) {
                return this.getSortClass(tab);
            }
            if (this.column == currentColumn && tab.sortKey !== this.column) {
                return "";
            }
        },
    },
    methods: {
        getTabObject: function () {
            var activeTab = this.$options.parent.$root.pagination.activeTab;
            var tab = [];
             $.each(this.$options.parent.$root.tabs, function (index, value) {
                if (value.label == activeTab) {
                    tab = this;
                }
            });
            return tab;
        },
        getSortClass: function (tab) {
            if (tab.sortOrder == -1) {
                return "fa-sort-desc";
            } else {
                return "fa-sort-asc";
            }
        }
    }
});
Vue.component('grid-search', {
    template: '<div class="table-search-block"><div class="row pull-right"><input type="text" class="form-control content-search filter" placeholder="Search" v-if="pagination[pagination.activeTab].render.pageNumbers.length" v-model="pagination[pagination.activeTab].search" @keyup.esc="clearForm"></div></div>',
    props: ['pagination'],
    methods: {
        clearForm: function() {
            this.pagination[this.pagination.activeTab].search = "";
        }
    }
});
Vue.component('grid-header-column', {
    template: '<th class="header" v-if="show" :class="sortable" @click="this.$dispatch(\'columnHeadClick\')" data-column="{{column}}" data-sort-order="null">{{ label | capitalize }}<sort-icons :column="column"></sort-icons></th>',
    props: ['column'],
    computed: {
        label: function () {
            if (["a", "input"].indexOf(this.column.split("_").pop()) >= 0) {
                return this.column.substring(0, this.column.lastIndexOf("_"));
            }
            return this.column;
        },
        sortable: function () {
            if (this.column.search('_input') == -1) {
                return "sortable";
            }
        },
        show: function () {
            var include = [];
            var exclude = [];
            var pagination = this.$options.parent.$root.pagination;
            $.each(this.$options.parent.$root.tabs, function (index, value) {
                if (value.label == pagination.activeTab) {
                    include = this.include;
                    exclude = this.exclude;
                }
            });
            if (include.length) {
                return include.indexOf(this.column) > -1;
            }
            if (exclude.length) {
                return exclude.indexOf(this.column) == -1;
            }
        },
    }
});
Vue.component('grid-header', {
    template: '<thead><tr><th is="grid-header-column" v-for="(col, val) in header" :column="col"></th></tr></thead>',
    props: ['header'],
    computed: {
        exclude: function () {
            if (this.col.search('_a') > -1 || this.col.search('_sort') > -1) {
                console.log(this.col);
                return true;
            }
            var pagination = this.$root.pagination;
            var exclude = [];
            $.each(this.$root.tabs, function (index, value) {
                if (value.label == pagination.activeTab) {
                    exclude = this.exclude;
                }
            });
            return exclude.indexOf(this.col) !== -1;
        }
    }
});

var vm = new Vue({
    el: '.labcoat-grid',
    data: labcoat_grid,
    methods: {
        setPage: function (tabLabel, pageNumber) {
            if (this.pagination[tabLabel].currentPage != pageNumber) {
                this.pagination[tabLabel].currentPage = pageNumber;
            }
            this.$nextTick(function () {
                this.initializeDeleteButton();
                this.highlightResults();
            });
        },
        previousPage: function (tabLabel) {
            var pageNumber = this.pagination[tabLabel].currentPage;
            if (pageNumber > 0) {
                this.setPage(tabLabel, pageNumber - 1);
            }
        },
        nextPage: function (tabLabel) {
            var pageNumber = this.pagination[tabLabel].currentPage;
            if (pageNumber < this.pagination[tabLabel].totalPages - 1) {
                this.setPage(tabLabel, pageNumber + 1);
            }
        },
        defineVisiblePageNumbers: function (tabLabel) {
            var pages = [];
            var countPages = this.pagination[tabLabel].totalPages;
            if (this.pagination[tabLabel].totalPages <= 10) {
                for (i=1; i <= countPages; i++) {
                    pages.push(i);
                }
                this.pagination[tabLabel].render.pageNumbers = pages;
                return true;
            }
            var countAround = 1;
            var out = '';
            var current = this.pagination[tabLabel].currentPage;
            var ellipses = [];
            for (i=1; i <= countPages; i++) {
                if (countAround >= 1 && i > 2 && i < countPages && Math.abs(i - current) > countAround) {
                    pages.push(i);
                    ellipses.push(i);
                    i = (i < current ? current - countAround : countPages - 1);
                }
                pages.push(i);
            }
            this.pagination[tabLabel].render.pageNumbers = pages;
            this.pagination[tabLabel].render.ellipses = ellipses;
            return true;
        },
        /*
        * Vue needs some time to update the model binding before highlighting can work.
        * use this.$nextTick() to delay that process.
        */
        highlightResults: function() {
            this.$nextTick(function () {
                $(this.selector).removeHighlight().highlight(this.pagination[this.pagination.activeTab].search);
            });
        },
        initializeDeleteButton: function() {
            $("#tabbed-table a[data-delete-url], #tabbed-table button[data-delete-url]").attr('data-delete-url', function(){
                var url = $(this).attr('data-delete-url');
                url = url.replace(/%7B\S+%7D/g, $(this).attr('data-delete-id'));
                $(this).attr('data-delete-url', url);
            })
            deleteSetup();
        }
    },
    created: function () {
        var self = this;
        $.each(this.tabs, function (index, value) {
            if (self.pagination.activeTab === "") {
                self.pagination.activeTab = value.label;
            }
            $.getJSON(this.url.replace(/&amp;/g, '&'), function (data) {
                this.data = data;
                self.tabs[index].loaded = !self.tabs[index].loaded;
            }.bind(this), function (data, index) {
                var tabLabel = data.label;
                self.updatePageCount(tabLabel);
            });
        });
    }
});
