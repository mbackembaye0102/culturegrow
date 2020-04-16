import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DetailsessioncollaborateurComponent } from './detailsessioncollaborateur.component';

describe('DetailsessioncollaborateurComponent', () => {
  let component: DetailsessioncollaborateurComponent;
  let fixture: ComponentFixture<DetailsessioncollaborateurComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DetailsessioncollaborateurComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DetailsessioncollaborateurComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
