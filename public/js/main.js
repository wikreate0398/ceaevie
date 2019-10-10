$(document).ready(function() {
    $(".menu_btn").click(function(){
        $(".menu_mobile").addClass("open");
        $("body").addClass("menu_opened");
    })
    $(".close_menu").click(function(){
        $(".menu_mobile").removeClass("open");
        $("body").removeClass("menu_opened");
    });

    $('.confirm-action').click(function(e){
        if (!confirm($(this).attr('data-confirm'))) {
            e.preventDefault();
        }
    });

    $(".language_select").text($(".language_list li.active[data-type='mobile-header']").text());
    $(".language_select").text($(".language_list li.active[data-type='desktop-header']").text());

    $(".language_list li").click(function(){
        var languageSelect = $(this).parents(".language").children(".language_select");
        var languageActive = $(this).text();

        $(".language_list li").removeClass("active");
        $(this).addClass("active");
        languageSelect.text(languageActive);
    });

    var maxSaleSlidesToShow = ($('.sale_item').length > 3) ? 3 : $('.sale_item').length;

    if ($('.sale_content').length) { 
        $('.sale_content').slick({
            slidesToShow: maxSaleSlidesToShow,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            arrow: false,
            prevArrow:'<i class="fa fa-angle-left arrow arrowL" aria-hidden="true"></i>',
            nextArrow:'<i class="fa fa-angle-right arrow arrowR" aria-hidden="true"></i>',
            // settings: "unslick",
            responsive: [
                {
                    breakpoint: 992,
                    settings: {
                        arrows: true,
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        arrows: true,
                        slidesToShow: 1
                    }
                }
            ]
        });
    }

    if ($('#dg-container').length) { 
        $('#dg-container').gallery();
    }

    var parent = document.getElementById("amount_digits");
    if($(parent).length)
    {
        var number = parent.getAttribute('data-content');
        for(var i = 0; i <= number.length-1; i++){
            var span = document.createElement("span");
            span.innerHTML = number[i];
            parent.appendChild(span);
        }
    }

});


var TableNav = function (table) {

    var self = this;
    this.table = table;

    var totalItems,
        itemsPerPage,
        personalBidLimit,
        userBids,
        tableItems,
        totalPages;

    this.currentPage = 1;

    this.init = function() {
        self.tableItems = $(this.table).find('.table_item');
        self.totalItems = $(self.tableItems).length;


        if(!self.totalItems)
        {
            return;
        }

        dataTable = jQuery.parseJSON($('#table_nav').attr('data-table'));

        this._setPersonalBidLimit(dataTable.personal_bid_limit);
        this._setUserBids(dataTable.user_bids);
        this._setItemsPerPage(dataTable.per_page);
        this._setTotalPages();

        this.perPageDOM();

        this.paginate();
    };

    this.perPageDOM = function () {
        var currentTotalItems = self.currentPage*self.itemsPerPage;

        if(currentTotalItems > self.totalItems){
            currentTotalItems = self.totalItems;
        }

        $(this.table).find('.item_per_page').text(
            currentTotalItems + '/' + self.totalItems
        );
    };

    this._setPersonalBidLimit = function (personal_bid_limit) {
        self.personalBidLimit = personal_bid_limit;
    };

    this._setUserBids = function (user_bids) {
        self.userBids = user_bids;
    };

    this._setItemsPerPage = function (per_page) {
        self.itemsPerPage = per_page;
    };

    this._setTotalPages = function () {
        self.totalPages = parseInt(self.totalItems/self.itemsPerPage);

        if(self.totalItems % self.itemsPerPage != 0){
            self.totalPages++;
        }
    };

    this.prev = function() {
        if(self.currentPage > 1){
            self.currentPage--;
        }

        self.paginate();
    };

    this.next = function() {

        if(self.currentPage < self.totalPages){
            self.currentPage++;
        }

        self.paginate();
    };

    this.paginate = function () {

        if(self.itemsPerPage < self.totalItems){
            var start = (self.currentPage == 1) ? 0 : (self.currentPage-1)*self.itemsPerPage;

            $(self.tableItems).each(function (i) {
                var item = i+1;
                if(item > start && item <= start+self.itemsPerPage){
                    $(this).show();

                    $(self.tableItems).removeClass('last');
                    if(item == start+self.itemsPerPage){
                        $(this).addClass('last');
                    }
                }else{
                    $(this).hide();
                }
            });
        }else{
            $(self.tableItems).last().addClass('last');
        }

        this.perPageDOM();

        if(self.currentPage == 1)
        {
            this.hidePrevArrow();
        }else{
            this.showPrevArrow();
        }

        if(self.currentPage == self.totalPages)
        {
            this.hideNextArrow();
        }else{
            this.showNextArrow();
        }
    };

    this.hidePrevArrow = function() {
        $(self.table).find('.arrow_l').hide();
    };

    this.showPrevArrow = function() {
        $(self.table).find('.arrow_l').show();
    };

    this.hideNextArrow = function() {
        $(self.table).find('.arrow_r').hide();
    };

    this.showNextArrow = function() {
        $(self.table).find('.arrow_r').show();
    };
}
var TableNav = new TableNav('#table_nav');
$(document).ready(function () {
    TableNav.init();
});