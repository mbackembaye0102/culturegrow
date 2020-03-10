import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AjoutcollaborateurComponent } from './ajoutcollaborateur.component';

describe('AjoutcollaborateurComponent', () => {
  let component: AjoutcollaborateurComponent;
  let fixture: ComponentFixture<AjoutcollaborateurComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AjoutcollaborateurComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AjoutcollaborateurComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
