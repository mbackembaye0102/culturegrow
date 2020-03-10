import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ListeteamComponent } from './listeteam.component';

describe('ListeteamComponent', () => {
  let component: ListeteamComponent;
  let fixture: ComponentFixture<ListeteamComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ListeteamComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ListeteamComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
