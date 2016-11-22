<div style="padding: 15px;">
    <table width="100%" id="dtable-news" class="table table-hover table-condensed" style="font-size: 10pt;">
        <thead>
            <tr>
                <th></th>
            </tr>
        </thead>
    </table>
</div>
<style type="text/css">
    #tbl1{
        height: 400px;
        overflow: auto;
    }
</style>
<script type="text/javascript">

    var dtable_news;
    $(function(){
        dtable_news = $('#dtable-news').DataTable( {
            "ajax": 'index.php?mod=common-ds&ajax=1&f=get_news',
            "aLengthMenu": [[5,10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            "iDisplayLength":5,
            "sDom":  '<"row"<"col-sm-6"<"#dt-title">> <"col-sm-6">><"row"<"col-md-12"<"#tbl1"t>>> <"row"<"col-sm-4"i> <"col-sm-8"p>> ',
            "language": {
                "lengthMenu": "Tampil _MENU_ record per halaman",
                "zeroRecords": "Tidak ada data yang ditemukan",
                "info": "Tampil _START_ - _END_ dari _TOTAL_ total",
                "infoEmpty": "Tidak ada record",
                "infoFiltered": "(disaring dari total _MAX_ record)" ,
                "paginate": {
                    "previous": "Sebelum",
                    "next": "Lanjut",
                    "first": "Awal",
                    "last": "Akhir",
                }
            },
            "order": [],
            "columns": [
                { "data": "news1"},
            ],
            "rowCallback": function( row, data, index ) {
                return dtable_news_row_callback( row, data, index )        
            },

            "columnDefs": [
                { 
                    "targets": 0, 
                    "sortable": false 
                } ,            
            ]                        

        } );

        $("#dt-title").html("<h4>Pengumuman</h4>");
        act_tooltipster();

    });

    function dtable_news_row_callback( row, data, index ) {
        var news1= data['news1'] ;

        var dt1=news1.substring(5,news1.indexOf("}}")) ;
        if(moment(dt1).isValid()){
            dt1= moment(dt1).format("dddd D MMM YYYY H:mm:ss")+" ("+moment(dt1).fromNow()+")";
        }
        var story1=news1.substring(news1.indexOf("}}")+2,1000000);

        var cell=$('td:eq(0)', row);
        cell=$(cell[0]);
        cell.html( "<i style='font-weight: bold'>"+dt1+"</i>"+story1);
        return true;
    }

</script>