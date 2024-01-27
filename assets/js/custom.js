(function (window, document, $, undefined) {
  "use strict";

  var sos3 = {
    i: function(e){
        sos3.d();
        sos3._baseurl = $('meta[name="baseurl"]').attr('content'); // Mueve esto aquí
        sos3.methods();
    },
    
    d: function (e) {
        this._window = $(window),
            this._document = $(document),
            this._body = $('body'),
            this._html = $('html'),
            this.sideNav = $('.rbt-search-dropdown');
    },
    methods: function(e){
        sos3.login();
    },

    login: function() {
        var baseUrl = this._baseurl; // Almacena la URL base en una variable

        $('#form-login').on('submit', function(e) {
            e.preventDefault(); // Previene el comportamiento predeterminado del formulario

            var formData = $(this).serialize(); // Recoge los datos del formulario

            $.ajax({
                url:'ajax/login', // Usa la variable baseUrl
                type: 'POST',
                data: formData,
                success: function(data) {
                    console.log(data);
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    }
  };

  $(document).ready(function() {
    sos3.i(); // Inicializa el objeto sos3 cuando el DOM está cargado
  });
})(window, document, jQuery);