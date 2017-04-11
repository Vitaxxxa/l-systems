function setHeiHeight() {
  $('#final-system').css({
    height: ($(window).height()-176) + 'px'
  });
}

function resizeViewBox(id){
  var obj = $('#'+id)[0];
  var bb  = obj.getBBox();
  var bbx = bb.x-5;
  var bby = bb.y-5;
  var bbw = bb.width+10;
  var bbh = bb.height+10;
  var vb  = [bbx,bby,bbw,bbh];

  obj.setAttribute('viewBox', vb.join(' '));
}

function svgToImg(object, index){
  var clone = $('#tmpl-card').clone();

  clone.find('.pic').html(object.image);
  clone.css('display','block');
  clone.attr('style','');
  clone.appendTo('#thumbs');

  resizeViewBox(object.id);
  var imge = clone.find('.pic').html();
  var b64 = btoa(imge);
  var src = "data:image/svg+xml;base64,\n"+b64;
  var img = $("<img class='thumb-pic' src='data:image/svg+xml;base64,\n"+b64+"' width='95%'/>");

  clone.find('.pic').html(img);
  clone.find('.thumb-gen').html(index);
  clone.find('.thumb-moves').html(object.moves);
  clone.find('.thumb-time').html(object.time);
  clone.find('.save-pic').attr('href',src);
  clone.find('.save-pic').attr('target','blank');
}

function parseData(){
  var axiom = $('#axiom').val();
  var gens  = $('#num-generations').val();
  var rules = {};
  var binds = {};
  var obj   = {};

  $('.rule-input').each(function(){
    var key    = $(this).find('.key').val();
    var value  = $(this).find('.value').val();
    rules[key] = value;
  });

  $('.bind-inputs').each(function(){
    var key    = $(this).find('#bind-key').val();
    var value  = $(this).find('#do-it').val();
    var param  = $(this).find('#param').val();
    binds[key] = {value:value, param:param};
  });

  obj.axiom = axiom;
  obj.gens  = gens;
  obj.binds = binds;
  obj.rules = rules;

  return obj;
}

$('#createSystem').on('click', function(event){
  event.preventDefault();
  var startTime = new Date().getSeconds() + '.' + new Date().getMilliseconds();
  var loadIcon  = $('.preloader-wrapper').clone().removeClass('hide');
  var data      = parseData();

  $('#final-system').empty();
  $('#final-system').append(loadIcon);

  $.post(
    "create/",
    {
      'axiom'      : data.axiom,
      'generations': data.gens,
      'rule'       : data.rules,
      'binds'      : data.binds
    },
    function(data){
      data = JSON.parse(data);

      $('#thumbs').empty();
      $('#final-system').empty();

      var mainImage       = data.pic.image;
      var mainId          = data.pic.id;
      var mainMoves       = data.pic.moves;
      var mainTime        = data.pic.time;
      var mainGenerations = data.pic.generations;
      var mainSource      = data.pic.source;
      var finishTime      = "";

      for (var i = 0; i < data.thumbs.length; i++) {
        svgToImg(data.thumbs[i], i);
      }
      
      $(mainImage).appendTo('#final-system');
      $('#dashboard').find('.php-time').text(mainTime);
      $('#dashboard').find('.moves').text(mainMoves);
      $('#dashboard').find('.genarations').text(mainGenerations);
      $('#dashboard').find('.source-main').text(mainSource);
      $('.tooltipped').tooltip({delay: 50}); 

      resizeViewBox(mainId);
      Materialize.toast('Ok', 5000, 'rounded');
      finishTime = (new Date().getSeconds() + '.' + new Date().getMilliseconds()) - startTime;
      $('#dashboard').find('.total-time').text(Number((finishTime).toFixed(3)));
    }
  ).fail(function(xhr, status, error) {
     Materialize.toast(xhr.status+' - '+error, 5000, 'rounded');
     $('#final-system').empty();
     $('#final-system').html(xhr.status+' - '+error);
  });
});

$('#add-new-rule').on('click', function(event){
  event.preventDefault();
  var cloned = $('.rule-input:first').clone();
  cloned.find('input').attr('id',' ');
  cloned.find('input').attr('value',' ');
  $('.rules-input').append(cloned);
});

$('#add-new-bind').on('click', function(event){
  event.preventDefault();
  var cloned = $('.bind-inputs:first').clone();
  cloned.find('input').val(' ');
  $('.bind-inputs').parent().prepend(cloned);
});

$(document).ready(function(){
  setHeiHeight();
  $('ul.tabs').tabs();
  $('select').material_select();
  $('.collapsible').collapsible();
  $('.modal').modal();
  $(window).resize( setHeiHeight );
  $('#createSystem').click();
});