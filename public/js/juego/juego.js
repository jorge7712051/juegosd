var timestamp = null;
var valor='';
var timer;
var llamada ;
function cargar_push() 
{ 
  
  
  $.ajax({
  async:  true, 
    type: "POST",
    url: "actualizar",
    data: "timestamp="+timestamp,
    dataType:"json",
    success: function(data)
  { 
        
   
      $.each(data, function(i, item) { 
        $("#anotar").show();    
        $("#actualizar").show();                
          timestamp= item.fecha;
          $("#puntajeoponente").html('<span><strong>Puntos: </strong>'+ item.puntosoponente+'</span>');
          $("#letrasoponente").html('<span><strong>Letras: </strong>'+ item.letrasoponente+'</span>');
          $("#tabla-juego").html(item.tabla);
          $("#puntaje").html('<p><strong><span><strong>Puntos: </strong>'+ item.puntos+'</span></p>');
          $("#letras").html('<p><strong>Letras: </strong><span id="letra"> '+ item.letras+'</span></p>');
            
            });
    
     //timer=setTimeout('cargar_push()',1000);
          
    }
  });   
}

function iniciar()
{
    recorrertabla();
    var tabla=$('#tabla-juego').html();
    var formData = new FormData();
    formData.append('tabla', tabla);
    $.ajax({
          type: "POST", 
          url: 'Iniciar',
          contentType:false,
          processData: false,
          dataType: "json",
          data: formData, 
          success: function(data) { 
            $("#iniciarjuego").hide();
            $.each(data, function(i, item) {               
                $("#nombrejugador").html('<p><strong>Nombre: </strong>'+ item.nombreusuario+'</p>');
                $("#puntaje").html('<p><strong>Puntos: </strong>'+ item.puntos+'</p>');
                $("#letras").html('<p><strong>Letras: </strong><span id="letra" '+ item.letras+'</span></p>');
                $("#tabla-juego").html(item.tabla);
            
            });
                cargar_push();
          }
          });
}

function guardarletra(b)
{ 
  
  var letra= b.value;
  if (letra!='')
  {   
    
      valor+=letra.toUpperCase();
  }
 
}
function anotar()
{
  //clearTimeout(timer);
  //llamada.abort();
  var palabra=valor;
  var letras=$('#letra').text();
  recorrertabla();
  tabla=$('#tabla-juego').html();
  var formData = new FormData();
  formData.append('valor', palabra);
  formData.append('tabla', tabla);
  formData.append('letras', letra);
  $.ajax({
          async:  true, 
          type: "POST", 
          url: 'Validar',
          contentType:false,
          processData: false,
          //dataType: "json",
          data: formData, 
          success: function(data) {
          // var myJSON = JSON.stringify(data);
          var mensaje = data.split('mensaje');    
          alert(data);         
           
               
          }
          });
}

function recorrertabla()
{  
  for (var i = 1 ; i <8 ; i++) 
  {
   for (var j = 1; j <8; j++)
   {
      valor= $("#"+i+j+" input").val();
      //alert(valor);
      if (valor=='')
      {
        $("#"+i+j+" input").attr("value","");
        //$("#"+i+j).val('0');
      }
      else
      {
         //$("#"+i+j).val(valor);
         $("#"+i+j+" input").attr("value",valor);
      }
   }
  }
}
$(document).ready(function()
{
 $("#anotar").hide();
 $("#actualizar").hide();
});  