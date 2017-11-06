const $ = require('jquery')
var datos

$.get({
    url:'/SIA/juridico/datos/Sessions',
    async:false,
    success: function(json){
        datos = JSON.parse(json)
    }
  })
  

  var nUsr=datos.idUsuario
  var nCampana=datos.idCuentaActual
  
  
  if(nUsr!="" && nCampana!=""){    
    var sPanel ="";
    var nRenglon=0;
    var nTotal=0;
    var dato;
    
    $.ajax({
      type: 'GET',
      url: '/SIA/lstModulosByUsuarioCampana/' + nCampana,
      success: function(response) {
        var jsonData = JSON.parse(response);
             
        nTotal =jsonData.datos.length;
     
        for (var i = 0; i < nTotal; i++) {
          dato = jsonData.datos[i];
          //modulos.push(dato.tipo, dato.panel, dato.modulo);
          
          sPanel = dato.panel;
          document.getElementById(sPanel).style.display="block";
          
          sPanel = sPanel + "-UL";
  
          var ul = document.getElementById(sPanel);
            var li = document.createElement("li");            
              var ancla = document.createElement('a');              
              ancla.setAttribute('href', dato.liga);
                //PARA CARGAR UN ICONO
                var icono = document.createElement("li");
                icono.setAttribute("class", dato.icono);              
                ancla.appendChild(icono);               
                
                var texto = document.createTextNode( " " + dato.nombre);
                ancla.appendChild(texto);               
              nRenglon = nRenglon+1;              
            li.appendChild(ancla);
          ul.appendChild(li); 
        }
      },
      error: function(xhr, textStatus, error){
        alert('ERROR: En function cargarMenu(nCampana)  ->  statusText: ' + xhr.statusText + ' TextStatus: ' + textStatus + ' Error:' + error );
      }     
    });  
     }else{
    if(nCampana=="")alert("Debe establecer una CUENTA PÚBLICA como PREDETERMINADA. Por favor consulte con su administrador del sistema.");
  }
  
  
  
  
  
  $(".has_sub > a").click(function(e){
    e.preventDefault();
    var menu_li = $(this).parent("li");
    var menu_ul = $(this).next("ul");
  
    if(menu_li.hasClass("open")){
      menu_ul.slideUp(350);
      menu_li.removeClass("open")
    }
    else{
      $("#nav > li > ul").slideUp(350);
      $("#nav > li").removeClass("open");
      menu_ul.slideDown(350);
      menu_li.addClass("open");
    }
  });
  
  
  $('div.buttonHide').click(function(){
    if(cont===0){
      $('ul#nav').animate({
        left:'-200px'
      })
      $(this).animate({
        left:'17px',
      })
      $(this).css('transform','rotate(180deg)')
      let div = $('div#tableLarge')
      div.removeClass('col-md-9')
      div.removeClass('col-md-offset-2')
      div.addClass('col-md-10')
      div.addClass('col-md-offset-1')
      cont+=1
    }
    else{
      $('ul#nav').animate({
        left:'0px'
      })
      $(this).animate({
        left:'217px'
      })
      $(this).css('transform','rotate(0deg)')
     
      let div = $('div#tableLarge')
      div.removeClass('col-md-10')
      div.removeClass('col-md-offset-1')
      div.addClass('col-md-9')
      div.addClass('col-md-offset-2')
      cont-=1
    }
     /*$('ul#nav').animate({
       left:'-200px'
     })
     $(this).animate({
       left:'17px'
     })*/
     
  })
  //$('.dropdown-toggle').dropdown()