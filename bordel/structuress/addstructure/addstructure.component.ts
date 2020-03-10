import { StructureService } from '../structure.service';
import { FormGroup, FormControl } from '@angular/forms';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-addstructure',
  templateUrl: './addstructure.component.html',
  styleUrls: ['./addstructure.component.scss']
})
export class AddstructureComponent implements OnInit {

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
