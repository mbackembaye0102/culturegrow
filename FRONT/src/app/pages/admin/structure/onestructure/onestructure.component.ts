import { Router, ActivatedRoute } from '@angular/router';
import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';

@Component({
  selector: 'app-onestructure',
  templateUrl: './onestructure.component.html',
  styleUrls: ['./onestructure.component.scss']
})
export class OnestructureComponent implements OnInit {

  constructor(private admin:AdminService,private router:ActivatedRoute) { }
 public id:any;
 rien:any;
 public infos:any;
  ngOnInit() {
    this.id=this.router.snapshot.params['id'];
    this.rien=this.id;
    console.log(this.id);
    this.id={'id':this.id};
    console.log(this.id);
     this.admin.oneteamstructure(this.id).subscribe(
       res=>{console.log(res);
        this.infos=res.body
       },
       error=>{console.log(error);
       }
     )
  }

}
