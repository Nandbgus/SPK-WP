<div class="row mt-3 justify-content-center">
  <div class="col-lg-10">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
      <nav class="navbar navbar-expand-lg bg-white">
        <div class="container justify-content-center">

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse justify-content-center" id="navbarNavAltMarkup">
            <ul class="navbar-nav text-center small"> 
              <li class="nav-item"><a class="nav-link px-3 fw-semibold text-primary" href="../home/home.php">Home</a></li>
              <li class="nav-item"><a class="nav-link px-3 fw-semibold text-primary" href="../alternatif/alternatifView.php">Alternatif</a></li>
              <li class="nav-item"><a class="nav-link px-3 fw-semibold text-primary" href="../kriteria/kriteriaView.php">Kriteria</a></li>
              <li class="nav-item"><a class="nav-link px-3 fw-semibold text-primary" href="../subKriteria/subKriteriaView.php">Sub-Kriteria</a></li>
              <li class="nav-item"><a class="nav-link px-3 fw-semibold text-primary" href="../faktor/faktorView.php">Faktor</a></li>
              <li class="nav-item"><a class="nav-link px-3 fw-semibold text-primary" href="../ranking/ranking.php">Ranking</a></li>
              <li class="nav-item"><a class="nav-link px-3 fw-semibold text-danger" href="../login/userLogout.php">Keluar</a></li>
            </ul>
          </div>

        </div>
      </nav>
    </div>
  </div>
</div>

<style>
  .navbar-nav .nav-link {
    transition: all 0.25s ease;
    border-radius: 6px;
    font-size: 0.9rem; 
  }

  .navbar-nav .nav-link:hover {
    background-color: #f3f9ff;
    color: #0056b3 !important;
    transform: translateY(-1px);
  }

  .navbar {
    padding-top: 0.3rem;
    padding-bottom: 0.3rem;
  }

  .card {
    border: none;
  }

  @media (max-width: 991px) {
    .navbar-nav .nav-link {
      display: block;
      margin: 5px 0;
      font-size: 0.95rem;
    }
  }
</style>
