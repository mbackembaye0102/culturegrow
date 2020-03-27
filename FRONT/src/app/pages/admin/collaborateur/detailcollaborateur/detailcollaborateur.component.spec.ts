import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DetailcollaborateurComponent } from './detailcollaborateur.component';

describe('DetailcollaborateurComponent', () => {
  let component: DetailcollaborateurComponent;
  let fixture: ComponentFixture<DetailcollaborateurComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DetailcollaborateurComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DetailcollaborateurComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
