@extends('layouts.master')

@section('title','LRI | Liste des projets')

@section('header_page')

<style>  table {
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
          var chartp = new CanvasJS.Chart("chartContainer",
    {
      title:{
        text: "le projets par equipes"
      }, 
       
		data: [
		 @foreach($projet_equipe as $va)
            {
		
                
              	type: "bar",
			dataPoints: [ 
             @foreach($projet_equipe1 as $varss)
            @if($varss->projet_id==$va->id)
             {  y: {{$varss->total}} , label:"{{$varss->achronymes}}" },
            @endif
            @endforeach
            ]  
		},@endforeach
                                   
            {
		
                
              	type: "bar",
			
			dataPoints: [ 
               @foreach($projet_equipe as $va)                      
              {  y:0 , label:"{{$va->id}}" },
                     @endforeach
            ]  
		}
		]
	});
     
chartp.render();


     }
</script>
<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
      <h1>
        Projets
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active"><a href="{{url('projets')}}">Projets</a></li>
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
            <li ><a href="{{url('membres')}}"><i class="fa fa-list"></i> Liste</a></li>
          </ul>
        </li>

         <li>
          <a href="{{url('theses')}}">
            <i class="fa fa-file-pdf-o"></i> 
            <span>Thèses</span>
          </a>
        </li>

         <li>
          <a href="{{url('articles')}}">
            <i class="fa fa-newspaper-o"></i> 
            <span>Articles</span></a>
          </li>

       
        <li class=" active">
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
              <h3 class="box-title">Liste des membres</h3>
            </div>
          </div>
          </div>
            
            <!-- /.box-header -->
            <div class="box-body">
               @if(Auth::user()->role->nom != 'membre' )
              <div class=" pull-right">
                <a href="{{url('projets/create')}}" type="button" class="btn btn-block btn-success btn-lg"><i class="fa fa-plus"></i> Nouveau Projet</a>
              </div>
             @endif
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Intitulé</th>
                  <th>Type</th>
                  <th>Partenaires</th>
                  <th>Chef</th>
                  <th>Membres</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>

                  @foreach($projets as $projet)
                  <tr>
                    <td>{{ $projet->intitule }}</td>
                    <td>{{ $projet->type }}</td>
                    <td>{{ $projet->partenaires }}</td>
                    <td><a href="{{url('membres/'.$projet->chef_id.'/details')}}">{{ $projet->chef->name}} {{ $projet->chef->prenom}}</a></td>
                    <td>
                      @foreach ($projet->users as $user) 
                      <ul>
                          <li><a href="{{url('membres/'.$user->id.'/details')}}">{{ $user->name }} {{ $user->prenom }}</a></li>
                      </ul>
                    @endforeach

                    </td>
                    <td>


                      <form action="{{ url('projets/'.$projet->id)}}" method="post"> 

                        {{csrf_field()}}
                        {{method_field('DELETE')}}
                      <a href="{{ url('projets/'.$projet->id.'/detail')}} " class="btn btn-info">
                        <i class="fa fa-eye"></i>
                      </a>
                      @if(Auth::user()->role->nom != 'membre' )
                      <a href="{{ url('projets/'.$projet->id.'/edit')}}" class="btn btn-default">
                        <i class="fa fa-edit"></i>
                      </a>
                      @endif
                      @if(Auth::user()->role->nom != 'membre' )
                      <!-- <button type="submit" class="btn btn-danger">
                        <i class="fa fa-trash-o"></i>
                      </button> -->
                       <a href="#supprimer{{ $projet->id }}Modal" role="button" class="btn btn-danger" data-toggle="modal"><i class="fa fa-trash-o"></i></a>
                      <div class="modal fade" id="supprimer{{ $projet->id }}Modal" tabindex="-1" role="dialog" aria-labelledby="supprimer{{ $projet->id }}ModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                              <div class="modal-content">
                                  <div class="modal-header">
                                    <!--   <h5 class="modal-title" id="supprimer{{ $projet->id }}ModalLabel">Supprimer</h5> -->
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                      </button>
                                  </div>
                                  <div class="modal-body text-center">
                                      Voulez-vous vraiment effectuer la suppression ? 
                                  </div>
                                  <div class="modal-footer">
                                      <form class="form-inline" action="{{ url('projets/'.$projet->id)}}"  method="POST">
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
                  <th>Intitulé</th>
                  <th>Type</th>
                  <th>Partenaires</th>
                  <th>Chef</th>
                  <th>Membres</th>
                  <th>Actions</th>
                </tr>
                </tfoot>
              </table>
            </div>
<br><br><br><br>
 <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">les projets par equipes</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
     <br><br><br>
<table> 
     
                                  
                                    
        <tr><th >équipe</th><th>code de projet</th> <th>fictif</th></tr>
        @foreach($projet_equipe1 as $varss)
        <tr><td>{{ $varss->achronymes }}</td><td>{{$varss->projet_id}}</td><td>{{$varss->total}}</td></tr>
         @endforeach
        
        </table>
     
    <br>
    <br>
    <br>
    <br>
   
    
    <div id="chartContainer" style="height: 300px; width: 100%;">
    </div>
</div>
            <!-- /.box-body -->
          </div>
        
      </div>
      
    </div>
    
  @endsection