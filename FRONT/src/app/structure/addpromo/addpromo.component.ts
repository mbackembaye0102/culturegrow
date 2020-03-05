import { Component, OnInit } from '@angular/core';
import { StructureService } from './../structure.service';
import { FormGroup, FormControl } from '@angular/forms';

@Component({
  selector: 'app-addpromo',
  templateUrl: './addpromo.component.html',
  styleUrls: ['./addpromo.component.scss']
})
export class AddpromoComponent implements OnInit {

  constructor(private structureservice:StructureService) { }

  ngOnInit() {
  }
  structure= new FormGroup({
    nom:new FormControl('')
  })
  save(donner){
console.log(donner);
this.structureservice.savestructure(donner).subscribe(
  res=>{
    console.log(res);
    
  },
  error=>{console.log(error);
  }
)
  }
}
