import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ListeposteComponent } from './listeposte.component';

describe('ListeposteComponent', () => {
  let component: ListeposteComponent;
  let fixture: ComponentFixture<ListeposteComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ListeposteComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ListeposteComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
