import { Router } from '@angular/router';
import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';

@Component({
  selector: 'app-listecollaborateur',
  templateUrl: './listecollaborateur.component.html',
  styleUrls: ['./listecollaborateur.component.scss']
})
export class ListecollaborateurComponent implements OnInit {
  private user:any;
  constructor(private auth:AuthService,private router:Router,private admin:AdminService) { }

  ngOnInit() {
  //  this.auth.chargementpage();
  //alert(this.auth.connecter)
    this.admin.listuser().subscribe(
      res=>{console.log(res);
        this.user=res;
      },
      error=>{console.log(error);
      }
    )
  }


}
