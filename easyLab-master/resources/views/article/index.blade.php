@extends('layouts.master')

 @section('title','LRI | Liste des articles')

@section('header_page')
<style>
  table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 10px;
  text-align: left;
  border-bottom: 1px solid #ddd;
}

tr:hover {background-color:#f5f5f5;}
</style>
   <script type="text/javascript">
window.onload = function () {
  var chart = new CanvasJS.Chart("chartContainer",
  {
    title:{
      text: "numbre d'article publié"
    },
    legend: {
      maxWidth: 350,
      itemWidth: 120
    },
    data: [
    {
      type: "pie",
      showInLegend: true,
      legendText: "{indexLabel}",
      dataPoints: [
                @foreach($var as $varss)
        { y: {{$varss->total}}, indexLabel: "{{$varss->type}}" },
                @endforeach
                
      ]
    }
    ]
  });
  chart.render();
        var chart1 = new CanvasJS.Chart("chartContainer1",
  {
    title:{
      text: "Nombre des articles publies par l'année"
    },
    axisY:{
      title:"Coal (bn tonnes)",
      valueFormatString: "#0.#,.",
    },
    data: [
     @foreach($var2 as $va)
            {
    
                
                type: "stackedColumn",
      legendText: "Anthracite & Bituminous",
      showInLegend: "true",
      dataPoints: [ 
             @foreach($vars as $varss)
            @if($varss->annee==$va->annee)
             {  y: {{$va->total}} , label:"{{$va->type}}" },
            @else
            {  y:0 , label:" " },
            @endif
            @endforeach
            ]  
    },@endforeach
                                   
            {
    
                
                type: "stackedColumn",
      legendText: "Anthracite & Bituminous",
      showInLegend: "true",
      dataPoints: [ 
             @foreach($vars as $varss)
             {  y:0, label:"{{$varss->annee}}" },
           
            @endforeach
            ]  
    }
    ]
  });
  chart1.render();
         var chart3 = new CanvasJS.Chart("chartContainer3",
    {
      title:{
        text: "Nombre des article publie par chaque équipe chaque année"
      }, 
       
    data: [
     @foreach($var3 as $va)
            {
    
                
                type: "bar",
      dataPoints: [ 
             @foreach($vars4 as $varss)
            @if($varss->annee==$va->annee)
             {  y: {{$va->total}} , label:"{{$va->achronymes}}" },
            @else
            {  y:0 , label:" " },
            @endif
            @endforeach
            ]  
    },@endforeach
                                   
            {
    
                
                type: "bar",
      
      dataPoints: [ 
             @foreach($vars4 as $varss)
             {  y:0, label:"{{$varss->annee}}" },
           
            @endforeach
            ]  
    }
    ]
  });
     

chart3.render();
}
</script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
      <h1>
        Articles
        <small>Liste</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">Articles</a></li>
      </ol>

@endsection

@section('asidebar')

        <li >
          <a href="{{url('dashboard')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>

        <li>
          <a href="{{url('equipes')}}">
            <i class="fa fa-group"></i> 
            <span>Equipes</span>
          </a>
        </li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-user"></i> <span>Membres</span>
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
          </a>
          <ul class="treeview-menu">
            <li ><a href="{{url('trombinoscope')}}"><i class="fa fa-id-badge"></i> Trombinoscope</a></li>
            <li class="active"><a href="{{url('membres')}}"><i class="fa fa-list"></i> Liste</a></li>
          </ul>
        </li>

         <li>
          <a href="{{url('theses')}}">
            <i class="fa fa-file-pdf-o"></i> 
            <span>Thèses</span>
          </a>
        </li>
      
         <li class="active">
          <a href="{{url('articles')}}">
            <i class="fa fa-newspaper-o"></i> 
            <span>Articles</span></a>
          </li>

           <li>
          <a href="{{url('projets')}}">
            <i class="fa fa-folder-open-o"></i> 
            <span>Projets</span>
          </a>
        </li>
        
          @if(Auth::user()->role->nom == 'admin' )

           <li>
          <a href="{{url('materiel')}}">
            <i class="fa fa-suitcase"></i> 
            <span>matériel</span></a>
          </li>

          <li>
          <a href="{{url('parametre')}}">
            <i class="fa fa-gears"></i> 
            <span>Paramètres</span></a>
          </li>
          @endif
  @endsection

@section('content')


    <div class="row">
      <div class="col-md-12">
        <div class="box col-xs-12">
          <div class="container" style="padding-top: 30px">
          <div class="row" style="padding-bottom: 20px">
             <div class="box-header col-xs-9">
              <h3 class="box-title">Liste des articles</h3>
            </div>
          </div>
          </div>
            
            <!-- /.box-header -->
            <div class="box-body">
           
              <div class="pull-right">
                <a href="{{url('articles/create')}}" type="button" class="btn btn-block btn-success btn-lg"><i class="fa fa-plus"> Nouvel article</i></a>
              </div>
              
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Type</th>
                  <th>Titre</th>
                  <th>Année</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <body>
                  @foreach($articles as $article)
                  <tr>
                    <td>{{$article->type}}</td>
                    <td>{{$article->titre}}</td>
                    <td>{{$article->annee}}</td>
                    <td>
                      <div class="btn-group">
                        <form action="{{ url('articles/'.$article->id)}}" method="post">
                          
                          {{csrf_field()}}
                          {{method_field('DELETE')}}

                        <a href="{{ url('articles/'.$article->id.'/details')}}" class="btn btn-info">
                            <i class="fa fa-eye"></i>
                        </a>
                        @if(Auth::user()->role->nom == 'admin' || Auth::user()->id == $article->deposer )
                        <a href="{{ url('articles/'.$article->id.'/edit')}}" class="btn btn-default">
                          <i class="fa fa-edit"></i>
                        </a>
                        @endif
                        @if( Auth::user()->role->nom != 'membre' || Auth::user()->id == $article->deposer )
                        <!-- <button type="submit" class="btn btn-danger ">
                            <i class="fa fa-trash-o"></i>
                        </button> -->

                         <a href="#supprimer{{ $article->id }}Modal" role="button" class="btn btn-danger" data-toggle="modal"><i class="fa fa-trash-o"></i></a>
                      <div class="modal fade" id="supprimer{{ $article->id }}Modal" tabindex="-1" role="dialog" aria-labelledby="supprimer{{ $article->id }}ModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header">
                                    <!--   <h5 class="modal-title" id="supprimer{{ $article->id }}ModalLabel">Supprimer</h5> -->
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body text-center">
                                      Voulez-vous vraiment effectuer la suppression ? 
                                  </div>
                                  <div class="modal-footer">
                                      <form class="form-inline" action="{{ url('articles/'.$article->id)}}"  method="POST">
                                          @method('DELETE')
                                          @csrf
                                      <button type="button" class="btn btn-light" data-dismiss="modal">Non</button>
                                          <button type="submit" class="btn btn-danger">Oui</button>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>

                        @endif
                        </form>
                    </div>
                    </td>
                  </tr>
                  @endforeach
                  
                 </tbody>
                <tfoot>
                <tr>
                  <th>Titre</th>
                  <th>Type</th>
                  
                  <th>Année</th>
                  <th>Actions</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <br><br><br>
      <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">numbre d'article publié</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <table>
        <th>type</th><th>le nombre d'article </th>
         @foreach($var as $varss)
<tr><td>{{$varss->type}}</td><td>{{$varss->total}}</td></tr>
                @endforeach
          
       
    </table>
 <br><br><br><br>
    <div id="chartContainer" style="height: 300px; width: 100%;">
    </div>
    </div>
<br><br><br>
      <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Nombre des articles publies par l'année </h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
       <table>
        <th>l'année</th><th>l'annee</th><th>le nombre d'article </th>
         @foreach($var2 as $varss)
<tr><td>{{$varss->type}}</td><td>{{$varss->annee}}</td><td>{{$varss->total}}</td></tr>
                @endforeach
          
       
    </table>
   <br><br><br><br>
    
    <div id="chartContainer1" style="height: 300px; width: 100%;">
    </div>
    </div>
    <br><br><br>
      <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Nombre des article publie par chaque équipe chaque année</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <table> 
          
        <tr><th >équipe</th><th>annee</th> <th>numbre d'article</th></tr>
        @foreach($var3 as $varss)
        <tr><td>{{ $varss->achronymes }}</td><td>{{$varss->annee}}</td><td>{{$varss->total}}</td></tr>
         @endforeach
        </table>
          <br><br><br><br>
    
   
    
    <div id="chartContainer3" style="height: 300px; width: 100%;">
    </div>
</div>
          </div>
            <!-- /.box-body -->
            
          </div>
        
      </div>
      
    </div>

 @endsection