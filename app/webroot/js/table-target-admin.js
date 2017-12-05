var target_admin = function () {
  "use strict"

  var layoutColors = ['#16a085', '#e74c3c', '#428bca', '#333333', '#6685a4', '#E68E8E']
  
  return { init: init, layoutColors: layoutColors, debounce: debounce }

  function init () {    
    initLayoutToggles ()  
    initNoticeBar ()

    navHoverInit ({ delay: { show: 250, hide: 350 } })

    initICheck ()
    initSelect2 ()
    initTableCheckable ()
    
    initScrollable ()
    initLightbox ()
    initEnhancedAccordion ()
    initDataTableHelper ()

    initFormValidation ()
    initTooltips ()
    initDatepicker ()
    initTimepicker ()
    initColorpicker ()
    initAutosize ()

    initBackToTop ()
  }

  function initScrollable () {
    if ($.fn.niceScroll) {
      $('.scrollable-panel').niceScroll ()
    }
  }

  function isLayoutCollapsed () {
    return $('.navbar-toggle').css ('display') == 'block'
  }

  function initLayoutToggles () {
    $('.navbar-toggle, .mainnav-toggle').click (function (e) {
      $(this).toggleClass ('is-open')
    })
  }

  function initNoticeBar () {
    $('.noticebar > li > a').click (function (e) {
      if (isLayoutCollapsed ()) {
        window.location = $(this).prop ('href')
      }
    })
  }

  function navHoverInit (config) {
    $('[data-hover="dropdown"]').each (function () {
      var $this = $(this),
          defaults = { delay: { show: 1000, hide: 1000 } },
          $parent = $this.parent (),
          settings = $.extend (defaults, config),
          timeout

      if (!('ontouchstart' in document.documentElement)) {
        $parent.find ('.dropdown-toggle').click (function (e) {
            if (!isLayoutCollapsed ()) {
              e.preventDefault ()
              e.stopPropagation ()
            }
        })
      }

      $parent.mouseenter(function () {
        if (isLayoutCollapsed ()) { return false }

        timeout = setTimeout (function () {
          $parent.addClass ('open')
          $parent.trigger ('show.bs.dropdown')
        }, settings.delay.show)
      })

      $parent.mouseleave(function () {
        if (isLayoutCollapsed ()) { return false }

        clearTimeout (timeout)

        timeout = setTimeout (function () {
          $parent.removeClass ('open keep-open')
          $parent.trigger ('hide.bs.dropdown')
        }, settings.delay.hide)
      })
    })
  }

  function initTableCheckable () {
    if ($.fn.tableCheckable) {
      $('.table-checkable')
            .tableCheckable ()
              .on ('masterChecked', function (event, master, slaves) { 
                  if ($.fn.iCheck) { $(slaves).iCheck ('update') }
              })
              .on ('slaveChecked', function (event, master, slave) {
                  if ($.fn.iCheck) { $(master).iCheck ('update') }
              })
    }
  }

  function initAutosize () {
    if ($.fn.autosize) {
    $('.ui-textarea-autosize').each(function() {
      if($(this).data ('animate')) {
          $(this).addClass ('autosize-animate').autosize()
        } else {
          $(this).autosize()
        }
      })
    }
  }

  function initEnhancedAccordion () {
    $('.accordion .accordion-toggle').on('click', function (e) {      
      $(e.target).parent ().parent ().parent ().addClass('open')
    })
  
    $('.accordion .accordion-toggle').on('click', function (e) {
      $(this).parents ('.panel').siblings ().removeClass ('open')
    })

    $('.accordion').each (function () {       
      $(this).find ('.panel-collapse.in').parent ().addClass ('open')
    })     
  }

  function initFormValidation () {
    if ($.fn.parsley) {
      $('.parsley-form').each (function () {
        $(this).parsley ({
          trigger: 'change',
          errors: {
            container: function (element, isRadioOrCheckbox) {
              if (element.parents ('form').is ('.form-horizontal')) {
                return element.parents ("*[class^='col-']")
              }

              return element.parents ('.form-group')
            }
          }
        })
      })
    }
  }

  function initLightbox () {
    if ($.fn.magnificPopup) {
      $('.ui-lightbox').magnificPopup({
        type: 'image',
        closeOnContentClick: false,
        closeBtnInside: true,
        fixedContentPos: true,
        mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
        image: {
          verticalFit: true,
          tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
        }
      })

      $('.ui-lightbox-video, .ui-lightbox-iframe').magnificPopup({
        disableOn: 700,
        type: 'iframe',
        mainClass: 'mfp-fade',
        removalDelay: 160,
        preloader: false,
        fixedContentPos: false
      })

      $('.ui-lightbox-gallery').magnificPopup({
        delegate: 'a',
        type: 'image',
        tLoading: 'Loading image #%curr%...',
        mainClass: 'mfp-img-mobile',
        gallery: {
          enabled: true,
          navigateByImgClick: true,
          preload: [0,1] // Will preload 0 - before current, and 1 after the current image
        },
        image: {
          tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
          titleSrc: function(item) {
            return item.el.attr('title') + '<small>by Marsel Van Oosten</small>'
          }
        }
      })
    }
  }

  function initSelect2 () {
    if ($.fn.select2) {
      $('.ui-select2').select2({ allowClear: true, placeholder: "Select..." })
    }
  }

  function initDatepicker () {
    if ($.fn.datepicker) { $('.ui-datepicker').datepicker ({ autoclose: true }) }
  }

  function initTimepicker () {
    if ($.fn.timepicker) { 
      var pickers = $('.ui-timepicker, .ui-timepicker-modal')

      pickers.each (function () {
        $(this).parent ('.input-group').addClass ('bootstrap-timepicker')

        if ($(this).is ('.ui-timepicker')) {
          $(this).timepicker ()
        } else {
          $(this).timepicker({ template: 'modal' })
        } 
      })   
    }
  }

  function initColorpicker () {
    if ($.fn.simplecolorpicker) {
      $('.ui-colorpicker').each (function (i) {
        var picker = $(this).data ('picker')

        $(this).simplecolorpicker({ 
          picker: picker
        })
      })
    }
  }

  function initTooltips () {
    if ($.fn.tooltip) { $('.ui-tooltip').tooltip () }
    if ($.fn.popover) { $('.ui-popover').popover ({ container: 'body' }) }
  }

  function initICheck () {
    if ($.fn.iCheck) {
      $('.icheck-input').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue',
        inheritClass: true
      }).on ('ifChanged', function (e) {
        $(e.currentTarget).trigger ('change')
      })
    }
  }

  function initBackToTop () {
    var backToTop = $('<a>', { id: 'back-to-top', href: '#top' })
    var icon = $('<i>', { class: 'fa fa-chevron-up' })

    backToTop.appendTo ('body')
    icon.appendTo (backToTop)
    
      backToTop.hide()

      $(window).scroll(function () {
          if ($(this).scrollTop() > 150) {
              backToTop.fadeIn ()
          } else {
              backToTop.fadeOut ()
          }
      })

      backToTop.click (function (e) {
        e.preventDefault ()

          $('body, html').animate({
              scrollTop: 0
          }, 600)
      })
  }

  function initDataTableHelper () {
    if ($.fn.dataTable) {
      $('[data-provide="datatable"]').each (function () { 
        $(this).addClass ('dataTable-helper')    
        var defaultOptions = {
            paginate: false,
            search: false,
            info: false,
            lengthChange: false,
            displayRows: 10
          },
          dataOptions = $(this).data (),
          helperOptions = $.extend (defaultOptions, dataOptions),
          $thisTable,
          tableConfig = {}

        tableConfig.iDisplayLength = helperOptions.displayRows
        tableConfig.bFilter = true
        tableConfig.bSort = true
        tableConfig.bPaginate = false
        tableConfig.bLengthChange = false  
        tableConfig.bInfo = false

        if (helperOptions.paginate) { tableConfig.bPaginate = false } // palani - dd - 10
        if (helperOptions.lengthChange) { tableConfig.bLengthChange = true }
        if (helperOptions.info) { tableConfig.bInfo = false }       
        if (helperOptions.search) { $(this).parent ().removeClass ('datatable-hidesearch') }       

        tableConfig.aaSorting = []
        tableConfig.aoColumns = []

        $(this).find ('thead tr th').each (function (index, value) {
          var sortable = ($(this).data ('sortable') === true) ? true : false
          tableConfig.aoColumns.push ({ 'bSortable': sortable })

          if ($(this).data ('direction')) {
            tableConfig.aaSorting.push ([index, $(this).data ('direction')])
          }
        })   
      
        // Create the datatable
        $thisTable = $(this).dataTable (tableConfig)

        if (!helperOptions.search) {
          $thisTable.parent ().find ('.dataTables_filter').remove ()
        }

        var filterableCols = $thisTable.find ('thead th').filter ('[data-filterable="true"]')

        if (filterableCols.length > 0) {
          var columns = $thisTable.fnSettings().aoColumns,
            $row, th, $col, showFilter
          
          $row = $('<tr>', { cls: 'dataTable-filter-row' }).appendTo ($thisTable.find ('thead'))

          for (var i=0; i<columns.length; i++) {
            $col = $(columns[i].nTh.outerHTML)
            showFilter = ($col.data ('filterable') === true) ? 'show' : 'hide'

            th = '<th class="' + $col.prop ('class') + '">'
            th += '<input type="text" class="form-control input-sm ' + showFilter + '" placeholder="' + $col.text () + '">'
            th += '</th>'
            $row.append (th)
          }
          //$('#palani').find ('th').removeClass ('sorting sorting_disabled sorting_asc sorting_desc sorting_asc_disabled sorting_desc_disabled') 
         $row.find ('th').removeClass ('sorting sorting_disabled sorting_asc sorting_desc sorting_asc_disabled sorting_desc_disabled')
        
         
          $thisTable.find ('thead input').keyup( function () {
          $thisTable.fnFilter( this.value, $thisTable.oApi._fnVisibleToColumnIndex( 
          $thisTable.fnSettings(), $thisTable.find ('thead input[type=text]').index(this) ) )			

          }) 
            
			
            $thisTable.addClass ('datatable-columnfilter')
        }
      })
      		//alert($thisTable.attr('id'));//freeze-table
      		//fxheaderInit('freeze-table',500,1,1) //palani table sort
            //fxheader() // palani freeze - call
           
      $('.dataTables_filter input').prop ('placeholder', 'Search...')
	   $('.dataTables_filter input').hide() //palani - Search hide....

    }
  }

  function debounce (func, wait, immediate) {
    var timeout, args, context, timestamp, result
    return function() {
      context = this
      args = arguments
      timestamp = new Date()

      var later = function() {
        var last = (new Date()) - timestamp

        if (last < wait) {
          timeout = setTimeout(later, wait - last)
        } else {
          timeout = null
          if (!immediate) result = func.apply(context, args)
        }
      }

      var callNow = immediate && !timeout

      if (!timeout) {
        timeout = setTimeout(later, wait)
      }

      if (callNow) result = func.apply(context, args)
      return result
    }
  }
}()


$(function () {
  target_admin.init ()
})