define(['jquery', 'core/ajax', 'core/str', 'core/config', 'core/templates', 'core/notification', 'core/modal_factory',], function ($, AJAX, str, mdlcfg, templates, notification, ModalFactory) {
    var usersTable = {  
    init : function() {   
      usersTable.commonEvents();
             
    },                 
    
    commonEvents:function () { 

       $(document).ready(function(){
             
          $('body').on('click','.addcourse', function(){
               
                var trackid = $('#id_selecttrack').val();
                var courseid = $('#id_selectcourse').val();
               
                var promises = AJAX.call([
                    {
                        methodname: 'get_track_courseid', 
                        args: {
                              trackid:trackid,
                              courseid:courseid,
          
                        }
                    }
            
               ]);
                promises[0].done(function (data) {
                    alert(data);
                });
          });

       });
       

   
    }

}
 return usersTable;
});