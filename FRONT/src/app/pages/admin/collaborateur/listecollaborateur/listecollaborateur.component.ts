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
  private usertampo:any;
  constructor(private auth:AuthService,private router:Router,private admin:AdminService) { }

  ngOnInit() {
  //  this.auth.chargementpage();
  //alert(this.auth.connecter)
    this.admin.usergrow().subscribe(
      res=>{console.log(res);
        this.user=res;
        this.usertampo=res;
      },
      error=>{console.log(error);
      }
    )
  }
  search(donner){
    console.log(donner);
    let a=this.user.filter(us => us.prenom.toLowerCase().search(donner)>=0)
  this.user=a;    
    if (donner=="") {
      this.user=this.usertampo;
    }
    
  }


}
