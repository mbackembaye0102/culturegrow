import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OnestructureComponent } from './onestructure.component';

describe('OnestructureComponent', () => {
  let component: OnestructureComponent;
  let fixture: ComponentFixture<OnestructureComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OnestructureComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OnestructureComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
