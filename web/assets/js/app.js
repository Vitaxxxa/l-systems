function setHeiHeight() {
    $('#final-system').css({
        height: ($(window).height()-176) + 'px'
    });
}

function resize(id){
  var obj = $('#'+id)[0];
  var bb=obj.getBBox();
	var bbx=bb.x-5
	var bby=bb.y-5
	var bbw=bb.width+10
	var bbh=bb.height+10
	var vb=[bbx,bby,bbw,bbh]
	obj.setAttribute('viewBox', vb.join(' ') )
}

function clone(object, index){
  var clone = $('#tmpl-card').clone();

  clone.find('.pic').html(object.image);
  clone.css('display','block');
  clone.attr('style','');
  clone.appendTo('#thumbs');

  resize(object.id);
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

$(document).ready(function(){
    setHeiHeight();
    $('ul.tabs').tabs();
    $('select').material_select();
    $('.collapsible').collapsible();
    $('.modal').modal();
    $(window).resize( setHeiHeight );
    $('#createSystem').click();

});

$('#createSystem').on('click', function(event){
	  event.preventDefault();

    var startTime = new Date().getSeconds() + '.' + new Date().getMilliseconds();

  	var axiom 	 = $('#axiom').val();
  	var gens 	   = $('#num-generations').val();
  	var rules 	 = {};
  	var binds 	 = {};
  	var loadIcon = $('.preloader-wrapper').clone().removeClass('hide');

  	$('#final-system').empty();
  	$('#final-system').append(loadIcon);

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

  	$.post(
      	"create/",
      	{
        	'axiom':axiom,
        	'generations':gens,
        	'rule': rules,
        	'binds':binds
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
            var thumbs          = data.thumbs;

            $(mainImage).appendTo('#final-system');
        	  resize(mainId);

            for (var i = 0; i < thumbs.length; i++) {
                clone(thumbs[i], i);
            }
            
            var finishTime = (new Date().getSeconds() + '.' + new Date().getMilliseconds()) - startTime;

            $('#dashboard').find('.total-time').text(Number((finishTime).toFixed(3)));
            $('#dashboard').find('.php-time').text(mainTime);
            $('#dashboard').find('.moves').text(mainMoves);
            $('#dashboard').find('.genarations').text(mainGenerations);
            $('#dashboard').find('.source-main').text(mainSource);

            Materialize.toast('Ok', 5000, 'rounded');
            $('.tooltipped').tooltip({delay: 50}); 
      	}
  	);

});

$('#add-new-rule').on('click', function(event){
	event.preventDefault();
  	var cloned = $('.rule-input:first').clone();
  	cloned.find('input').attr('id','');
  	cloned.find('input').attr('value','');
  	$('.rules-input').prepend(cloned);
});

$('#add-new-bind').on('click', function(event){
    event.preventDefault();
    var cloned = $('.bind-inputs:first').clone();
    $('.bind-inputs').parent().prepend(cloned);
});