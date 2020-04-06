import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AdminService } from 'src/app/service/admin.service';

@Component({
  selector: 'app-listeteam',
  templateUrl: './listeteam.component.html',
  styleUrls: ['./listeteam.component.scss']
})
export class ListeteamComponent implements OnInit {

  constructor(private auth:AuthService,private route:ActivatedRoute,private admin:AdminService,private router:Router) { }
  public id:any;
  private team:any;
  ngOnInit() {
    //this.auth.chargementpage()
    // this.id=this.route.snapshot.params['id'];
    // let a={'id':this.id}
    // console.log(this.id);
    this.admin.listeteamgrow().subscribe(
      res=>{console.log(res);
       // let r=res.body;
        this.team=res;
      },
      error=>{console.log(error);
      }
    )
    
  }
next(){
 // this.admin.idteam=this.id;
  this.router.navigate(['/team/add']);
}
}
