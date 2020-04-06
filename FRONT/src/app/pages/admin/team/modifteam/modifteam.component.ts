import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { AdminService } from 'src/app/service/admin.service';

@Component({
  selector: 'app-modifteam',
  templateUrl: './modifteam.component.html',
  styleUrls: ['./modifteam.component.scss']
})
export class ModifteamComponent implements OnInit {
  public id:any;
  public user:any;
  constructor(private auth:AuthService,private route:ActivatedRoute,private admin:AdminService) { }

  ngOnInit() {
    this.id=this.route.snapshot.params['id'];
    console.log(this.id);
    let a={id:this.id}
    this.admin.userteam(a).subscribe(
      res=>{console.log(res.body);
        this.user=res.body;
      },
      error=>{console.log(error);
      }
    )
    this.auth.chargementpage()
  }


}
