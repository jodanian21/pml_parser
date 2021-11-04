<nav class="navbar navbar-expand-sm bg-success navbar-dark">
    <a class="navbar-brand" href="#">Pizza</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="{{ route('index') }}"><i class="fa fa-upload"></i> Upload File</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('input') }}"><i class="fa fa-pencil"></i> Input Order</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('list') }}"><i class="fa fa-table"></i> Order List</a>
        </li>
      </ul>
    </div>  
  </nav>