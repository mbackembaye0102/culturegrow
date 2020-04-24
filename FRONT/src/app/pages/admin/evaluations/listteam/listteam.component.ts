import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';
import { AuthService } from 'src/app/service/auth.service';

@Component({
  selector: 'app-listteam',
  templateUrl: './listteam.component.html',
  styleUrls: ['./listteam.component.scss']
})
export class ListteamComponent implements OnInit {
  public team:any;
  constructor(private admin:AdminService,public auth:AuthService) { }

  ngOnInit() {
    this.admin.titrepage="EVALUATION";
    console.log(this.auth.utilisateur);
    if (this.auth.isevaluation) {
      let a={
        id:this.auth.utilisateur.iduser,
        team:this.auth.utilisateur.teamevaluer
      }
      this.team=this.auth.utilisateur.teamevaluer;
      console.log(a);
      
    }
  }

}
