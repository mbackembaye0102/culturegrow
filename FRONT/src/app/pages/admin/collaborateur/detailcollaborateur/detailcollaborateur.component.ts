import { AdminService } from './../../../../service/admin.service';
import { ActivatedRoute } from '@angular/router';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-detailcollaborateur',
  templateUrl: './detailcollaborateur.component.html',
  styleUrls: ['./detailcollaborateur.component.scss']
})
export class DetailcollaborateurComponent implements OnInit {
  private id:string;
  private user:any;
  constructor(private route:ActivatedRoute,private admin:AdminService) { }

  ngOnInit() {
    this.id=this.route.snapshot.params['id'];
    let a={id:this.id};
    this.admin.detailuser(a).subscribe(
      res=>{
        console.log(res.body);
        this.user=res.body;
      },
      error=>{
        console.log(error);
        
      }
    )
  }

}
