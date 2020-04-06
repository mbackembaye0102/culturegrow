import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-userteamstructure',
  templateUrl: './userteamstructure.component.html',
  styleUrls: ['./userteamstructure.component.scss']
})
export class UserteamstructureComponent implements OnInit {
  public id:any;
  public user:any;
  constructor(private admin:AdminService,private activeroute:ActivatedRoute) { }

  ngOnInit() {
    this.id=this.activeroute.snapshot.params['id'];
    console.log(this.id);
    let a={id:this.id}
    this.admin.userteam(a).subscribe(
      res=>{console.log(res.body);
        this.user=res.body;
      },
      error=>{console.log(error);
      }
    )
  }

}
