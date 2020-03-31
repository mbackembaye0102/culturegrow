import { AdminService } from './../../../../service/admin.service';
import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-ajoutteam',
  templateUrl: './ajoutteam.component.html',
  styleUrls: ['./ajoutteam.component.scss']
})
export class AjoutteamComponent implements OnInit {

  constructor(private auth:AuthService,private admin:AdminService) { }

  ngOnInit() {
   // this.auth.chargementpage()
   console.log(this.admin.idteam);
   
  }


}
