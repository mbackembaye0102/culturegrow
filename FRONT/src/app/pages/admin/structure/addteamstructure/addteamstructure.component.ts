import { FormGroup, FormControl } from '@angular/forms';
import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-addteamstructure',
  templateUrl: './addteamstructure.component.html',
  styleUrls: ['./addteamstructure.component.scss']
})
export class AddteamstructureComponent implements OnInit {

  constructor(private admin:AdminService,private route:ActivatedRoute,private router:Router) { }
  id:any;
  ngOnInit() {
    this.id=this.route.snapshot.params['id'];
  }
  structure= new FormGroup({
    nom:new FormControl(''),
    id: new FormControl('')
  })
  save(donner){
    console.log(donner);
    donner.id=this.id;
    this.admin.saveoneteamstructure(donner).subscribe(
      res=>{
        console.log(res);
        this.router.navigate(['/onestructure/',this.id])
      },
      error=>{
        console.log(error);
        
      }
    )
  }
}
