import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AdduserteamstructureComponent } from './adduserteamstructure.component';

describe('AdduserteamstructureComponent', () => {
  let component: AdduserteamstructureComponent;
  let fixture: ComponentFixture<AdduserteamstructureComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AdduserteamstructureComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AdduserteamstructureComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
