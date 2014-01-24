/*global translation: true*/

/*TOOLBAR FOR DOCUMENTS WITH ADDITIONAL OPTIONS*/
jQuery(document).ready(function () {
    var document = jQuery('.htmlexDoc'),
        documentData = document.data(),
        documentId = documentData.documentId,
        docToolbar = jQuery('#docsToolbar'),
        intervalMain,
        intervalRunnable = true,
        pageScroller = true;

    /*RECALCULATE PERCENT OF LOADED DOCUMENT*/
    function docPercentLoad() {
        var main = jQuery('.toolbarActions .docPagesAll'),
            percents = Math.round(((documentData.currentPackage / documentData.packages).toFixed(2)) * 100);

        if (percents !== 100) {
            main.find('span').html(_mPHeart.translation.LC_DANE_TOOLBAR_LOADED_DOC_AT + ' ' + percents + "%");
        } else {
            main.find('span').html(_mPHeart.translation.LC_DANE_TOOLBAR_LOADING_ALL);
            main.find('a').hide();
        }

    }

    /*IF USER HIT BOTTOM OF PAGE LOAD NEW PART OF PAGES*/
    function searchLoadMoreDoc(page) {
        var convPage = hexDecConv(page, 'hex'),
            documentCanvas = jQuery('.document .canvas div[data-page-no=' + convPage + ']');

        pageScroller = false;

        if (!documentCanvas.length && intervalRunnable && (documentData.currentPackage < documentData.packages)) {
            loadMoreDoc('searchLoadMoreDoc(' + page + ')');
        } else {
            jQuery('html, body').animate({
                scrollTop: documentCanvas.offset().top + 10
            }, 1000, function () {
                var canvasP = jQuery('.canvas .p');

                docToolbar.find('input[name="document_page"]').val(page);
                currPage[0] = page;
                currPage[1] = (jQuery(canvasP[currPage[0] - 1]).length) ? jQuery(canvasP[currPage[0] - 1]).offset().top : 'none';
                currPage[2] = (jQuery(canvasP[currPage[0]]).length) ? jQuery(canvasP[currPage[0]]).offset().top : 'none';
                pageScroller = true;
            });
        }
    }

    /*FUNCTION LOAD NEXT PACKAGE OF DOCUMENT UNTIL ALL DOCUMENT WILL FULLY LOADED*/
    function loadAllDoc() {
        if (intervalRunnable && (documentData.currentPackage < documentData.packages)) {
            intervalRunnable = false;
            jQuery('.loadMoreDocumentContent').addClass('loading');
            jQuery.get('/dane/' + documentData.dataset + '/' + documentId + '/view/' + (parseInt(documentData.currentPackage) + 1) + '.json', function (data) {
                jQuery('.loadMoreDocumentContent').removeClass('loading');
                document.find('.canvas').append(data);
                intervalRunnable = true;
                documentData.currentPackage = documentData.currentPackage + 1;
                docPercentLoad();
                eval(loadAllDoc());
            });
        }
    }

    /*FUNCTION LOAD NEXT PACKAGE OF DOCUMENT*/
    function loadMoreDoc(callback) {
        intervalRunnable = false;
        jQuery('.loadMoreDocumentContent').addClass('loading');
        jQuery.get('/dane/' + documentData.dataset + '/' + documentId + '/view/' + (parseInt(documentData.currentPackage) + 1) + '.json', function (data) {
            jQuery('.loadMoreDocumentContent').removeClass('loading');
            document.find('.canvas').append(data);
            intervalRunnable = true;
            documentData.currentPackage = documentData.currentPackage + 1;
            docPercentLoad();
            if (callback) {
                var getType = {};
                if (callback && getType.toString.call(callback) === '[object Function]') {
                    (callback)(jQuery);

                } else {
                    eval(callback);
                }
            }
        });
    }

    /*CONVERT HEX CONVENTION OF NUMERICAL PAGE TO DECIMAL AND OTHER WAY*/
    function hexDecConv(number, way) {
        var convertNumber;

        if (way == "dec") {
            convertNumber = parseInt(number, 16);
        } else if (way == "hex") {
            convertNumber = Number(number).toString(16);
        }
        return convertNumber;
    }

    /*CHECK IS DECLARATED ELEMENT IS VISIBLE AT BROWSER*/
    function isElementVisibled(elem) {
        if (jQuery(elem).length) {
            var docViewTop = jQuery(window).scrollTop();
            var docViewBottom = docViewTop + jQuery(window).height();
            var elemTop = jQuery(elem).offset().top;
            var elemBottom = elemTop + jQuery(elem).height();
            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
        } else {
            return false;
        }
    }

    /*LOAD NEXT PAGE AFTER SCROLLED TO BOTTOM OF LAST LOADED PAGE*/
    if (jQuery('.loadMoreDocumentContent').length) {
        intervalMain = setInterval(function () {
            if (isElementVisibled('.loadMoreDocumentContent') && intervalRunnable) {
                if (documentData.currentPackage < documentData.packages) {
                    intervalRunnable = false;
                    jQuery('.loadMoreDocumentContent').addClass('loading');
                    loadMoreDoc(function () {
                        window.setTimeout(function () {
                            var canvasCurrentPage = jQuery('.canvas .p')[currPage[0]];
                            currPage[2] = (jQuery(canvasCurrentPage).length) ? jQuery(canvasCurrentPage).offset().top : 'none';
                        }, 500)
                    });
                } else if (documentData.currentPackage = documentData.packages) {
                    var docsToolbarPagesAll = jQuery('#docsToolbar').find('.docPagesAll a');
                    clearInterval(intervalMain);
                    jQuery('.loadMoreDocumentContent').remove();
                    docPercentLoad();
                    if (docsToolbarPagesAll.length > 0)
                        docsToolbarPagesAll.addClass('disabled');
                }
            }

        }, 1500);
    }

    if (docToolbar.length) {
        docPercentLoad();

        /*LOAD ALL UNLOADED PAGE OF DOCUMENT*/
        docToolbar.find('.docPagesAll a').click(function (e) {
            var pageAll;
            e.preventDefault();

            if (!((pageAll = jQuery(this)).hasClass('disabled'))) {
                clearInterval(intervalMain);
                jQuery('.loadMoreDocumentContent').remove();
                pageAll.addClass('disabled');
                loadAllDoc();
            }
        });

        /*PAGE CHANGE by INPUT*/
        docToolbar.find('input[name="document_page"]').bind('blur keyup', function (e) {
            var intRegex = /^\d+$/,
                page;
            if (e.type === 'keyup' && e.keyCode !== 10 && e.keyCode !== 13) return;
            if (intRegex.test((page = jQuery(this).val().replace(/^\s\s*/, '').replace(/\s\s*$/, ''))) && (page <= documentData.pages)) {
                searchLoadMoreDoc(page);
            }
        });
    }

    /*PAGE CHANGE by SCROLL TO NEXT*/
    var currPage = [1, 'none'];
    jQuery(window).scroll(function () {
        if (pageScroller) {
            var window_top = jQuery(window).scrollTop(),
                canvasP = jQuery('.canvas .p');

            if (!currPage[2])
                (jQuery(canvasP[1]).length) ? currPage[2] = jQuery(canvasP[1]).offset().top : currPage[2] = 'none';

            if (window_top < currPage[1]) {
                currPage[0] = hexDecConv(((jQuery(canvasP[currPage[0] - 2]).attr('data-page-no')) ? (jQuery(canvasP[currPage[0] - 2]).attr('data-page-no')) : 0), 'dec');
                docToolbar.find('input[name="document_page"]').val((currPage[0]) ? currPage[0] : 1);

                currPage[1] = (jQuery(canvasP[currPage[0] - 1]).length) ? jQuery(canvasP[currPage[0] - 1]).offset().top : 'none';
                currPage[2] = (jQuery(canvasP[currPage[0]]).length) ? jQuery(canvasP[currPage[0]]).offset().top : 'none';
            } else if (window_top > currPage[2]) {
                currPage[0] = hexDecConv((jQuery(canvasP[currPage[0]]).attr('data-page-no')), 'dec');
                docToolbar.find('input[name="document_page"]').val(currPage[0]);

                currPage[1] = (jQuery(canvasP[currPage[0] - 1]).length) ? jQuery(canvasP[currPage[0] - 1]).offset().top : 'none';
                currPage[2] = (jQuery(canvasP[currPage[0]]).length) ? jQuery(canvasP[currPage[0]]).offset().top : 'none';
            }
        }
    });

    if (docToolbar.length > 0)
        sticky('#docsToolbar');
});